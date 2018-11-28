<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'RKW.' . $_EXTKEY,
	'Rkwrssfeed',
	array(
		'Rss' => 'rss, instantArticles',
		
	),
	// non-cacheable actions
	array(
		'Rss' => 'rss, instantArticles',
		
	)
);


// set logger
$GLOBALS['TYPO3_CONF_VARS']['LOG']['RKW']['RkwRss']['writerConfiguration'] = array(

	// configuration for WARNING severity, including all
	// levels with higher severity (ERROR, CRITICAL, EMERGENCY)
	\TYPO3\CMS\Core\Log\LogLevel::WARNING => array(
	// add a FileWriter
		'TYPO3\\CMS\\Core\\Log\\Writer\\FileWriter' => array(
			// configuration for the writer
			'logFile' => 'typo3temp/logs/tx_rkwrss.log'
		)
	),
);