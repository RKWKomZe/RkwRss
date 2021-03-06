<?php

/***************************************************************
 * Extension Manager/Repository config file for ext: "rkw_rss"
 *
 * Auto generated by Extension Builder 2016-09-28
 *
 * Manual updates:
 * Only the data in the array - anything else is removed by next write.
 * "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = [
	'title' => 'RKW RSS',
	'description' => 'RSS-Feed with included contents, so it can be used e.g. for Facebook Instant Articles',
	'category' => 'plugin',
	'author' => 'Steffen Kroggel',
	'author_email' => 'developer@steffenkroggel.de',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => '0',
	'createDirs' => '',
	'clearCacheOnLoad' => 0,
	'version' => '8.7.52',
	'constraints' => [
		'depends' => [
            'typo3' => '7.6.0-8.7.99',
            'rkw_basics' => '8.7.16-8.7.99',
        ],
		'conflicts' => [
		],
		'suggests' => [
		],
	],
];