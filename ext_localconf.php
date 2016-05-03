<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$_EXTKEY,
	'Tx_CsPowermailLimit2_Limit',
	array(
		'Limit' => 'checkLimit',	
	),
	// non-cacheable actions
	array(
		'Limit' => 'checkLimit',
		
	)
);
$signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\SignalSlot\Dispatcher');
$signalSlotDispatcher->connect('Tx_Powermail_Controller_FormsController', 'formActionBeforeRenderView', 'Tx_CsPowermailLimit2_Limit', 'checkLimit', FALSE);
?>