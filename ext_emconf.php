<?php

########################################################################
# Extension Manager/Repository config file for ext "cs_powermail_limit".
#
# Auto generated 10-03-2012 17:19
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Powermail Limit',
	'description' => 'Makes it possible to set a Limit to Powermail.',
	'category' => 'fe',
	'author' => 'Christian Strunk',
	'author_email' => 'Christian.M.Strunk@googlemail.com',
	'shy' => '',
	'dependencies' => 'cms,powermail',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '0.0.2',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'powermail' => '',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:14:{s:9:"ChangeLog";s:4:"24bb";s:10:"README.txt";s:4:"ee2d";s:21:"ext_conf_template.txt";s:4:"c914";s:12:"ext_icon.gif";s:4:"ad4f";s:17:"ext_localconf.php";s:4:"2006";s:14:"ext_tables.php";s:4:"af80";s:14:"ext_tables.sql";s:4:"c034";s:71:"flexform_field.powermailinformation_tx_cspowermaillimit_pi_flexform.xml";s:4:"5081";s:55:"flexform_tt_content_tx_cspowermaillimit_pi_flexform.xml";s:4:"f457";s:16:"locallang_db.xml";s:4:"95df";s:19:"doc/wizard_form.dat";s:4:"0a37";s:20:"doc/wizard_form.html";s:4:"8378";s:37:"pi1/class.tx_cspowermaillimit_pi1.php";s:4:"334b";s:17:"pi1/locallang.xml";s:4:"76c6";}',
	'suggests' => array(
	),
);

?>