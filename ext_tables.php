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

?>