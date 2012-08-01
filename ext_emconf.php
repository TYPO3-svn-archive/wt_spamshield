<?php

########################################################################
# Extension Manager/Repository config file for ext "wt_spamshield".
#
# Auto generated 01-08-2012 16:52
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Spamshield',
	'description' => 'Spam shield without captcha to avoid spam in powermail, ve_guestbook, comments and standard TYPO3 mailforms. Session check, Link check, Time check, Akismet check, Name check, Honeypot check (see manual for details)',
	'category' => 'services',
	'shy' => 0,
	'version' => '0.8.0',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'beta',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 0,
	'lockType' => '',
	'author' => 'Alex Kellner',
	'author_email' => 'alexander.kellner@in2code.de',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'php' => '4.0.0-0.0.0',
			'typo3' => '3.5.0-0.0.0',
		),
		'conflicts' => array(
			'mf_akismet' => '0.0.0-9.9.9',
			'wt_calculating_captcha' => '0.0.0-0.0.0',
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:27:{s:21:"ext_conf_template.txt";s:4:"8b66";s:12:"ext_icon.gif";s:4:"8a2a";s:17:"ext_localconf.php";s:4:"9ce4";s:14:"ext_tables.php";s:4:"f293";s:14:"ext_tables.sql";s:4:"71a2";s:28:"icon_tx_wtspamshield_log.gif";s:4:"f140";s:16:"locallang_db.xml";s:4:"e43e";s:7:"tca.php";s:4:"1e98";s:14:"doc/manual.sxw";s:4:"b09e";s:38:"ext/class.tx_wtspamshield_comments.php";s:4:"882a";s:45:"ext/class.tx_wtspamshield_defaultmailform.php";s:4:"9e75";s:39:"ext/class.tx_wtspamshield_powermail.php";s:4:"868c";s:42:"ext/class.tx_wtspamshield_ve_guestbook.php";s:4:"45e4";s:27:"functions/akismet.class.php";s:4:"5021";s:39:"functions/class.tx_wtspamshield_div.php";s:4:"49e0";s:39:"functions/class.tx_wtspamshield_log.php";s:4:"e579";s:40:"functions/class.tx_wtspamshield_mail.php";s:4:"ade1";s:44:"lib/class.tx_wtspamshield_method_akismet.php";s:4:"6fe3";s:46:"lib/class.tx_wtspamshield_method_blacklist.php";s:4:"d6ac";s:45:"lib/class.tx_wtspamshield_method_honeypod.php";s:4:"34ba";s:46:"lib/class.tx_wtspamshield_method_httpcheck.php";s:4:"0036";s:46:"lib/class.tx_wtspamshield_method_namecheck.php";s:4:"e5d4";s:44:"lib/class.tx_wtspamshield_method_session.php";s:4:"df92";s:43:"lib/class.tx_wtspamshield_method_unique.php";s:4:"0ca6";s:32:"static/defaultmailform/setup.txt";s:4:"0aa3";s:29:"static/settings/constants.txt";s:4:"977a";s:25:"static/settings/setup.txt";s:4:"c23e";}',
);

?>