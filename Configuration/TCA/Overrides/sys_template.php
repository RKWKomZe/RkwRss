<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey)
    {
        //=================================================================
        // Add TypoScript
        //=================================================================
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
            'rkw_rss',
            'Configuration/TypoScript',
            'RKW Rss'
        );
    },
    'rkw_rss'
);

