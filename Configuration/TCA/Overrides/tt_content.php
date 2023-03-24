<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey)
    {
        //=================================================================
        // Register Plugin
        //=================================================================
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'RKW.RkwRss',
            'Rkwrssfeed',
            'RKW RSS'
        );
},
    'rkw_rss'
);

