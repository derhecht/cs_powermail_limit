<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    $_EXTKEY,
    'Tx_CsPowermailLimit_Limit',
    array(
        'Limit' => 'checkLimit',
    ),
    // non-cacheable actions
    array(
        'Limit' => 'checkLimit',

    )
);

\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher')
    ->connect('In2code\\Powermail\\Controller\\FormController', 'formActionBeforeRenderView',
        'Tx_CsPowermailLimit_Limit', 'checkLimit', false);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_befunc.php']['getFlexFormDSClass'][]
    = \EWERK\CsPowermailLimit\Hooks\FlexFormHook::class;
?>