<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Alexander Kellner <Alexander.Kellner@einpraegsam.net>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

require_once(PATH_tslib . 'class.tslib_pibase.php');
require_once(t3lib_extMgm::extPath('wt_spamshield') . 'lib/class.tx_wtspamshield_method_session.php');
require_once(t3lib_extMgm::extPath('wt_spamshield') . 'lib/class.tx_wtspamshield_method_httpcheck.php');
require_once(t3lib_extMgm::extPath('wt_spamshield') . 'lib/class.tx_wtspamshield_method_namecheck.php');
require_once(t3lib_extMgm::extPath('wt_spamshield') . 'lib/class.tx_wtspamshield_method_akismet.php');
require_once(t3lib_extMgm::extPath('wt_spamshield') . 'lib/class.tx_wtspamshield_method_unique.php');
require_once(t3lib_extMgm::extPath('wt_spamshield') . 'lib/class.tx_wtspamshield_method_honeypod.php');
require_once(t3lib_extMgm::extPath('wt_spamshield') . 'functions/class.tx_wtspamshield_log.php');
require_once(t3lib_extMgm::extPath('wt_spamshield') . 'functions/class.tx_wtspamshield_mail.php');
require_once(t3lib_extMgm::extPath('wt_spamshield') . 'functions/class.tx_wtspamshield_div.php');

class tx_wtspamshield_defaultmailform extends tslib_pibase {

	var $honeypod_inputName = 'wt_spamshield_honey';
	
	/**
	 * Function generateSession() is called if the form is rendered (generate a session)
	 *
	 * @return	void
	 */
	function generateSession() {
		$this->div = t3lib_div::makeInstance('tx_wtspamshield_div'); // Generate Instance for div method
		
		if ( // only if spamshield should be activated for standardMailform
			!empty($GLOBALS['TSFE']->tmpl->setup['plugin.']['wt_spamshield.']['enable.']['standardMailform']) &&
			$this->div->spamshieldIsNotDisabled()
		) {
			$this->div->checkConf(); // Check Extension Manager configuration
			
			// Set session on form create
			$method_session_instance = t3lib_div::makeInstance('tx_wtspamshield_method_session'); // Generate Instance for session method
			$method_session_instance->setSessionTime(); // Start setSessionTime() Function: Set session if form is loaded
		}
	}
	
	/**
	 * Function sendFormmail_preProcessVariables() is called after submit - stop mail if needed
	 *
	 * @param	object		$form: Form Object
	 * @param	object		$obj: Parent Object
	 * @param	array		$legacyConfArray: legacy configuration
	 * @return	object		$form
	 */
	function sendFormmail_preProcessVariables($form, $obj, $legacyConfArray = array()) {
		// config
		$error = ''; // no error at the beginning
		$this->div = t3lib_div::makeInstance('tx_wtspamshield_div'); // Generate Instance for div method
		$this->messages = $GLOBALS['TSFE']->tmpl->setup['plugin.']['wt_spamshield.']['message.']; // Get messages from TS
		
		if ( // only if spamshield should be activated for standardMailform
			!empty($GLOBALS['TSFE']->tmpl->setup['plugin.']['wt_spamshield.']['enable.']['standardMailform']) &&
			$this->div->spamshieldIsNotDisabled()
		) {
		
			// 1a. sessionCheck
			if (!$error) {
				$method_session_instance = t3lib_div::makeInstance('tx_wtspamshield_method_session'); // Generate Instance for session method
				$error .= $method_session_instance->checkSessionTime($this->messages['session.']['note1'], $this->messages['session.']['note2'], $this->messages['session.']['note3']);
			}
			
			// 1b. httpCheck
			if (!$error) {
				$method_httpcheck_instance = t3lib_div::makeInstance('tx_wtspamshield_method_httpcheck'); // Generate Instance for httpCheck method
				$error .= $method_httpcheck_instance->httpCheck($form, $this->messages['httpcheck']);
			}
			
			// 1c. uniqueCheck
			if (!$error) {
				$method_unique_instance = t3lib_div::makeInstance('tx_wtspamshield_method_unique'); // Generate Instance for uniqueCheck method
				$error .= $method_unique_instance->main($form, $this->messages['uniquecheck']);
			}
			
			// 1d. honeypodCheck
			if (!$error) {
				$method_honeypod_instance = t3lib_div::makeInstance('tx_wtspamshield_method_honeypod'); // Generate Instance for honeypod method
				$method_honeypod_instance->inputName = $this->honeypod_inputName; // name for input field
				$error .= $method_honeypod_instance->checkHoney($form, $this->messages['honeypod']);
			}
			
			// 2a. Safe log file
			if ($error) {
				$method_log_instance = t3lib_div::makeInstance('tx_wtspamshield_log'); // Generate Instance for logging method
				$method_log_instance->dbLog('standardMailform', $error, $form);
			}
			
			// 2b. Send email to admin
			if ($error) {
				$method_sendEmail_instance = t3lib_div::makeInstance('tx_wtspamshield_mail'); // Generate Instance for email method
				$method_sendEmail_instance->sendEmail('standardMailform', $error, $form);
			}
			
			// 2c. Redirect and stop mail sending
			if (!empty($error)) { // If error
				$link = (!empty($GLOBALS['TSFE']->tmpl->setup['plugin.']['wt_spamshield.']['redirect.']['standardMailform']) ? $GLOBALS['TSFE']->tmpl->setup['plugin.']['wt_spamshield.']['redirect.']['standardMailform'] : t3lib_div::getIndpEnv('TYPO3_SITE_URL')); // redirection link - take only Domain if no target in TS
				header('HTTP/1.1 301 Moved Permanently'); 
				header('Location: ' . $link); 
				header('Connection: close');
				return false; // no return, so no email will be sent
			}
			
		}
		
		return $form; // default: return values to send email
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wt_spamshield/ext/class.tx_wtspamshield_defaultmailform.php']) {
	include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wt_spamshield/ext/class.tx_wtspamshield_defaultmailform.php']);
}
?>