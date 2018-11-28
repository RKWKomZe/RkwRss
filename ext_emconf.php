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

$EM_CONF[$_EXTKEY] = array(
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
	'version' => '7.6.2',
	'constraints' => array(
		'depends' => array(
            'extbase' => '6.2.0-7.6.99',
            'fluid' => '6.2.0-7.6.99',
            'typo3' => '6.2.0-7.6.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
);