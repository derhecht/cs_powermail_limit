<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}
$tempColumns = array (
	'tx_cspowermaillimit_limit' => array (	
		'exclude' => '1',	
		'label' => 'LLL:EXT:cs_powermail_limit/locallang_db.xml:tt_content.tx_cspowermaillimit_limit',		
		'config' => array (
			'type' => 'input',	
			'size' => '5',	
			'eval' => 'int,nospace'
			)
			),
	'tx_cspowermaillimit_record' => array (
		'exclude' => '1',
		'label' => 'LLL:EXT:cs_powermail_limit/locallang_db.xml:tt_content.tx_cspowermaillimit_record',
		'config' => array(
			'type' => 'group',
			'internal_type' => 'db',
			'allowed' => 'tt_content',
			'size' => '1',
			'maxitems' => '1',
			'minitems' => '0',
			'show_thumbs' => '1',
			),
			),
	'tx_cspowermaillimit_record_start' => array (
		'exclude' => '1',
		'label' => 'LLL:EXT:cs_powermail_limit/locallang_db.xml:tt_content.tx_cspowermaillimit_record_start',
		'config' => array(
			'type' => 'group',
			'internal_type' => 'db',
			'allowed' => 'tt_content',
			'size' => '1',
			'maxitems' => '1',
			'minitems' => '0',
			'show_thumbs' => '1',
			),
			),
	'tx_cspowermaillimit_record_end' => array (
		'exclude' => '1',
		'label' => 'LLL:EXT:cs_powermail_limit/locallang_db.xml:tt_content.tx_cspowermaillimit_record_end',
		'config' => array(
			'type' => 'group',
			'internal_type' => 'db',
			'allowed' => 'tt_content',
			'size' => '1',
			'maxitems' => '1',
			'minitems' => '0',
			'show_thumbs' => '1',
			),
			),
	'tx_cspowermaillimit_starttime' => array (
		'exclude' => '1',
        'label' => 'LLL:EXT:cs_powermail_limit/locallang_db.xml:tt_content.tx_cspowermaillimit_starttime',
        'config' => array(
			'type' => 'input',
            'size' => '10',
            'max' => '20',
            'eval' => 'datetime',
            'default' => '0'
            ),
            ),
	'tx_cspowermaillimit_enddate' => array (
		'exclude' => '1',
        'label' => 'LLL:EXT:cs_powermail_limit/locallang_db.xml:tt_content.tx_cspowermaillimit_enddate',
        'config' => array(
			'type' => 'input',
            'size' => '10',
            'max' => '20',
            'eval' => 'datetime',
            'default' => '0',
            ),
            ),
            );

            //Loda TCA config
            t3lib_div::loadTCA('tt_content');
            t3lib_extMgm::addTCAcolumns('tt_content',$tempColumns,1);

            // Make a Divider
            t3lib_extMgm::addToAllTCAtypes('tt_content','--div--;LLL:EXT:cs_powermail_limit/locallang_db.xml:tx_cspowermaillimit_divider',powermail_pi1);

            // Set the palettes
            t3lib_extMgm::addToAllTCAtypes('tt_content','--palette--;LLL:EXT:cs_powermail_limit/locallang_db.xml:tx_cspowermaillimit_maillimit;cs_PM_maillimit;;;',powermail_pi1,'after:LLL:EXT:cs_powermail_limit/locallang_db.xml:tx_cspowermaillimit_divider');
            t3lib_extMgm::addToAllTCAtypes('tt_content','--palette--;LLL:EXT:cs_powermail_limit/locallang_db.xml:tx_cspowermaillimit_records;cs_PM_Limit;;;',powermail_pi1,'after:LLL:EXT:cs_powermail_limit/locallang_db.xml:tx_cspowermaillimit_maillimit');
            t3lib_extMgm::addToAllTCAtypes('tt_content','--palette--;LLL:EXT:cs_powermail_limit/locallang_db.xml:tx_cspowermaillimit_timelimits;cs_PM_timelimits;;;',powermail_pi1,'after:tx_cspowermaillimit_limit');
            t3lib_extMgm::addToAllTCAtypes('tt_content','--palette--;LLL:EXT:cs_powermail_limit/locallang_db.xml:tx_cspowermaillimit_records;cs_PM_timelimits_records;;;',powermail_pi1,'after:LLL:EXT:cs_powermail_limit/locallang_db.xml:tx_cspowermaillimit_timelimits');

            // Palette Maillimit
            $TCA["tt_content"]["palettes"]["cs_PM_maillimit"]["showitem"] = "tx_cspowermaillimit_limit";
            $TCA["tt_content"]["palettes"]["cs_PM_maillimit"]['canNotCollapse'] = "1";
            $TCA["tt_content"]["palettes"]["cs_PM_Limit"]["showitem"] = "tx_cspowermaillimit_record";

            // Palette Timelimit
            $TCA["tt_content"]["palettes"]["cs_PM_timelimits"]["showitem"] = "tx_cspowermaillimit_starttime, tx_cspowermaillimit_enddate";
            $TCA["tt_content"]["palettes"]["cs_PM_timelimits"]['canNotCollapse'] = "1";
            $TCA["tt_content"]["palettes"]["cs_PM_timelimits_records"]["showitem"] = "tx_cspowermaillimit_record_start, tx_cspowermaillimit_record_end";

            // add show free space field

            t3lib_div::loadTCA('tx_powermail_fields');
            $TCA['tx_powermail_fields']['columns']['formtype']['config']['items'][] = array('LLL:EXT:cs_powermail_limit/locallang_db.xml:fieldtitle.tx_cspowermaillimit_freeplaces', 'tx_cspowermaillimit_freeplaces');

            ?>