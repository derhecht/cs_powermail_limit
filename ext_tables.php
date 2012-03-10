<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}
$tempColumns = array (
	'tx_cspowermaillimit_pi_flexform' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:cs_powermail_limit/locallang_db.xml:tt_content.tx_cspowermaillimit_pi_flexform',		
		'config' => array (
			'type' => 'flex',
			'ds' => array (
				'default' => 'FILE:EXT:cs_powermail_limit/flexform_tt_content_tx_cspowermaillimit_pi_flexform.xml',
			),
		)
	),
);

// TCA fŸr tt_content laden
t3lib_div::loadTCA('tt_content');
// Add Field to TCA
t3lib_extMgm::addTCAcolumns('tt_content',$tempColumns,1);
// make a new divider 
t3lib_extMgm::addToAllTCAtypes('tt_content','--div--;LLL:EXT:cs_powermail_limit/locallang_db.xml:tx_cspowermaillimit.powermail.divider.title;;;1-1-1',powermail_pi1);
// Flexform einfuegen
t3lib_extMgm::addToAllTCAtypes('tt_content','tx_cspowermaillimit_pi_flexform;;;;1-1-1', powermail_pi1, 'after:LLL:EXT:cs_powermail_limit/locallang_db.xml:tx_cspowermaillimit.powermail.divider.title');

// EXTRA Field
// load TCA for tx_powermail_fuelds
t3lib_div::loadTCA('tx_powermail_fields');
// set Field
$TCA['tx_powermail_fields']['columns']['formtype']['config']['items'][] = array('LLL:EXT:cs_powermail_limit/locallang_db.xml:tx_cspowermaillimit.powermail.field.limitinformation', 'tx_cspowermaillimit_limitinformation');
// set flexform
$TCA["tx_powermail_fields"]["columns"]["flexform"]["config"]["ds"]["tx_cspowermaillimit_limitinformation"] = 'FILE:EXT:cs_powermail_limit/flexform_field.limitinformation_tx_cspowermaillimit_pi_flexform.xml';

?>