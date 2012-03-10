<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

t3lib_extMgm::addPItoST43($_EXTKEY, 'pi1/class.tx_cspowermaillimit_pi1.php', '_pi1', '', 1);

// Hook form Limit-Ext
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['powermail']['PM_MainContentHookAfter'][] = 'EXT:cs_powermail_limit/pi1/class.tx_cspowermaillimit_pi1.php:tx_cspowermaillimit_pi1';
// Hook for Limit Information
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['powermail']['PM_FieldHook']['tx_cspowermaillimit_limitinformation'][] = 'EXT:cs_powermail_limit/pi1/class.tx_cspowermaillimit_pi1.php:tx_cspowermaillimit_pi1';
?>