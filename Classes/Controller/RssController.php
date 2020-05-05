<?php

namespace RKW\RkwRss\Controller;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class RssController
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwRss
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class RssController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{


    /**
     * @var \RKW\RkwRss\Cache\RssCache
     * @inject
     */
    protected $cache;

    /**
     * pagesRepository
     *
     * @var \RKW\RkwRss\Domain\Repository\PagesRepository
     * @inject
     */
    protected $pagesRepository;

    /**
     * pagesRepository
     *
     * @var \RKW\RkwRss\Domain\Repository\PagesLanguageOverlayRepository
     * @inject
     */
    protected $pagesLanguageOverlayRepository;


    /**
     * @var \TYPO3\CMS\Core\Log\Logger
     */
    protected $logger;


    /**
     * Get all RSS entries
     *
     * @return void
     */
    public function rssAction()
    {

        $this->view->assignMultiple($this->getContents('rss'));

    }

    /**
     * Get all RSS entries for InstantArticles
     *
     * @return void
     */
    public function instantArticlesAction()
    {

        $this->view->assignMultiple($this->getContents('instantArticles'));

    }


    /**
     * Get all RSS contents
     *
     * @param string $type defines with typo of site is to be generated
     * @return array
     */
    protected function getContents($type = 'rss')
    {

        if (!in_array($type, array('rss', 'instantArticles'))) {
            $type = 'rss';
        }

        $feed = [
            'currentTime'   => time(),
            'buildTime' => time(),
            'publicationTime' => time(),
            'pages' => [],
            'feedLanguage'  => $GLOBALS['TSFE']->config['config']['htmlTag_langKey'],
            'type'          => $type,
            'settings'      => array_merge($this->settings['global'], $this->settings[$type]) // override settings with type-specific settings
        ];

        if (!$feedCache = $this->getCache()->getContent($type)) {

            $maxResults = intval($this->settings['global']['limit']) ? intval($this->settings['global']['limit']) : 10;
            $orderField = $this->settings['global']['orderField'] ? $this->settings['global']['orderField'] : 'lastUpdated';

            //=============================
            // 1. get pages
            $pages = null;
            $languageUid = $GLOBALS['TSFE']->sys_language_uid;
            try {
                if ($languageUid > 0) {
                    $pages = $this->pagesLanguageOverlayRepository->findLatest($languageUid, $orderField, $maxResults);

                } else {
                    $pages = $this->pagesRepository->findLatest($orderField, $maxResults);
                }

            } catch (\Exception $e) {
                $this->getLogger()->log(\TYPO3\CMS\Core\Log\LogLevel::ERROR, sprintf('An error occurred while trying to fetch relevant pages for RSS feed. Message: %s', str_replace(array("\n", "\r"), '', $e->getMessage())));
            }

            //=============================
            // 2. set some results and cache it
            if ($pages) {
                $feedCache = [
                    'publicationTime' => $pages->getFirst()->getPublicationTime(),
                    'buildTime' => time(),
                    'pages' => $pages
                ];

                // flush caches
                $this->getCache()->getCacheManager()->flushCachesByTag('rkwrss_contents');

                // save results in cache
                $this->getCache()->setContent(
                    $feedCache,
                    array(
                        'rkwrss_contents',
                    )
                );
            }

            $this->getLogger()->log(\TYPO3\CMS\Core\Log\LogLevel::INFO, sprintf('Successfully rebuilt RSS feed.'));

        } else {
            $this->getLogger()->log(\TYPO3\CMS\Core\Log\LogLevel::INFO, sprintf('Successfully loaded RSS feed from cache.'));
        }

        // override data from cache array
        if ($feedCache) {
            $feed['buildTime'] = $feedCache['buildTime'];
            $feed['publicationTime'] = $feedCache['publicationTime'];
            $feed['pages'] = $feedCache['pages'];
        }

        return $feed;
    }


    /**
     * Returns logger instance
     *
     * @return \TYPO3\CMS\Core\Log\Logger
     */
    protected function getLogger()
    {

        if (!$this->logger instanceof \TYPO3\CMS\Core\Log\Logger) {
            $this->logger = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Log\LogManager')->getLogger(__CLASS__);
        }

        return $this->logger;
    }


    /**
     * Returns the cache object
     *
     * @return \RKW\RkwRss\Cache\RssCache
     */
    protected function getCache()
    {

        if (!$this->cache instanceof \RKW\RkwRss\Cache\RssCache) {
            $this->cache = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('RKW\\RkwRss\\Cache\\RssCache');
        }

        return $this->cache;
    }

}