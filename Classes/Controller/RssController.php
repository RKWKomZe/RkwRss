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
     * pagesLanguageOverlayRepository
     *
     * @var \RKW\RkwRss\Domain\Repository\PagesLanguageOverlayRepository
     * @inject
     */
    protected $pagesLanguageOverlayRepository;


    /**
     * ttContentRepository
     *
     * @var \RKW\RkwRss\Domain\Repository\TtContentRepository
     * @inject
     */
    protected $ttContentRepository;


    /**
     * @var \TYPO3\CMS\Core\Log\Logger
     */
    protected $logger;



    /**
     * Get all RSS entries
     *
     * @return string
     */
    public function rssAction()
    {

        if (!$feed = $this->getCache()->getContent($this->getCacheKey('rss'))) {

            $this->view->assignMultiple($this->getViewVariables('rss'));
            $feed = $this->view->render();

            // flush caches
            $this->getCache()->getCacheManager()->flushCachesByTag('rkwrss_contents');

            // save results in cache
            $this->getCache()->setContent(
                $feed,
                array(
                    'rkwrss_contents',
                ),
                $this->getCacheKey('rss')
            );

            $this->getLogger()->log(\TYPO3\CMS\Core\Log\LogLevel::INFO, sprintf('Successfully rebuilt RSS feed.'));
        } else {
            $this->getLogger()->log(\TYPO3\CMS\Core\Log\LogLevel::INFO, sprintf('Successfully loaded RSS feed from cache.'));
        }

        return $feed;

    }

    /**
     * Get all RSS entries for InstantArticles
     *
     * @return string
     */
    public function instantArticlesAction()
    {
        
        if (!$feed = $this->getCache()->getContent($this->getCacheKey('instantArticles'))) {

            $this->view->assignMultiple($this->getViewVariables('instantArticles'));
            $feed = $this->view->render();

            // flush caches
            $this->getCache()->getCacheManager()->flushCachesByTag('rkwrss_contents');

            // save results in cache
            $this->getCache()->setContent(
                $feed,
                array(
                    'rkwrss_contents',
                ),
                $this->getCacheKey('instantArticles')
            );

            $this->getLogger()->log(\TYPO3\CMS\Core\Log\LogLevel::INFO, sprintf('Successfully rebuilt RSS feed.'));
        } else {
            $this->getLogger()->log(\TYPO3\CMS\Core\Log\LogLevel::INFO, sprintf('Successfully loaded RSS feed from cache.'));
        }

        return $feed;
    }


    /**
     * Get all RSS contents
     *
     * @param string $type defines with typo of site is to be generated
     * @return array
     */
    protected function getViewVariables($type = 'rss')
    {

        if (!in_array($type, array('rss', 'instantArticles'))) {
            $type = 'rss';
        }

        // override global settings with type-specific settings
        $mergedSettings = array_merge(
            $this->settings['global'],
            $this->settings[$type]
        );

        $config = $this->getConfig($type);

        // define basic values now
        $feed = [
            'currentTime'   => time(),
            'buildTime' => time(),
            'publicationTime' => time(),
            'pages' => [],
            'feedLanguage'  => $GLOBALS['TSFE']->config['config']['htmlTag_langKey'],
            'type'          => $type,
            'settings'      => $mergedSettings,
        ];


        //=============================
        // 1. get pages
        $pages = null;
        $languageUid = $GLOBALS['TSFE']->sys_language_uid;
        try {
            if ($languageUid > 0) {
                $pages = $this->pagesLanguageOverlayRepository->findLatest($config['rootPid'], $languageUid, $config['orderField'], $config['maxResults']);
            } else {
                $pages = $this->pagesRepository->findLatest($config['rootPid'], $config['orderField'], $config['maxResults']);
            }

        } catch (\Exception $e) {
            $this->getLogger()->log(\TYPO3\CMS\Core\Log\LogLevel::ERROR, sprintf('An error occurred while trying to fetch relevant pages for RSS feed. Message: %s', str_replace(array("\n", "\r"), '', $e->getMessage())));
        }


        if ($pages) {

            //=============================
            // 2. get contents of pages
            try {

               /** @var \RKW\RkwRss\Domain\Model\Pages $page */
                foreach ($pages as $page) {

                    if ($languageUid > 0) {
                        /** @var \RKW\RkwRss\Domain\Model\PagesLanguageOverlay $page */
                        $page->setContents($this->ttContentRepository->findAllByColumn($page->getPid(), $config['contentColumn'], $languageUid));

                    } else {
                        $page->setContents($this->ttContentRepository->findAllByColumn($page->getUid(), $config['contentColumn']));
                    }

                    $feed['pages'][] = $page;
                }

            } catch (\Exception $e) {
                $this->getLogger()->log(\TYPO3\CMS\Core\Log\LogLevel::ERROR, sprintf('An error occurred while trying to fetch relevant contents for RSS feed. Message: %s', str_replace(array("\n", "\r"), '', $e->getMessage())));
            }
        }

        return $feed;
    }



    /**
     * Get all configuration
     *
     * @param string $type defines with typo of site is to be generated
     * @return array
     */
    protected function getConfig($type = 'rss')
    {

        if (!in_array($type, array('rss', 'instantArticles'))) {
            $type = 'rss';
        }

        // override global settings with type-specific settings
        $mergedSettings = array_merge(
            $this->settings['global'],
            $this->settings[$type]
        );
        
        return [
            'mergedSettings' => $mergedSettings,
            'maxResults' => intval($mergedSettings['limit'] ? $mergedSettings['limit'] : 10),
            'orderField' => preg_replace(
                '#[^a-zA-Z0-9_-]+#',
                '',
                $mergedSettings['orderField'] ? $mergedSettings['orderField'] : 'lastUpdated'
            ),
            'contentColumn' => preg_replace(
                '#[^a-zA-Z0-9_-]+#',
                '',
                $mergedSettings['contentColumn'] ? $mergedSettings['contentColumn'] : 0
            ),
            'rootPid' => intval(
                $mergedSettings['rootPid'] ? $mergedSettings['rootPid'] : 1
            )
        ];
    }


    /**
     * Returns cache key
     *
     * @param string $type defines with typo of site is to be generated
     * @return string
     */
    protected function getCacheKey($type = 'rss')
    {
        
        if (!in_array($type, array('rss', 'instantArticles'))) {
            $type = 'rss';
        }

        $config = $this->getConfig($type);
        return $type . '_' . $config['rootPid'] . '_' . intval($GLOBALS['TSFE']->sys_language_uid) . '_' . $config['contentColumn'] . '_' . $config['orderField'];
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