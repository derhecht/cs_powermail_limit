<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    $_EXTKEY,
    'Pi1',
    array(
        #...
        'Limit' => 'checkLimit',
        #...
    ),
    array(
        'Limit' => 'checkLimit'
    )
);

if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('powermail')) {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('powermail_pi1',
        'FILE:EXT:cs_powermail_limit/Configuration/FlexForms/flexform_ds.xml');
}

?>