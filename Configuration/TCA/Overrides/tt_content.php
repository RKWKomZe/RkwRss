<?php
defined('TYPO3_MODE') || die('Access denied.');

//=================================================================
// Register Plugin
//=================================================================
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'RKW.RkwRss',
    'Rkwrssfeed',
    'RKW RSS'
);