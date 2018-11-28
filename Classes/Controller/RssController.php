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

        $maxResults = intval($this->settings[$type]['limit']) ? intval($this->settings[$type]['limit']) : 10;
        $contentColPos = intval($this->settings[$type]['contentColPos']) ? intval($this->settings[$type]['contentColPos']) : 0;
        $orderField = $this->settings[$type]['orderField'] ? $this->settings[$type]['orderField'] : 'crdate';
        $pubDate = $lastBuildDate = 0;

        //=============================
        // 1. get updated pages
        $pages = null;
        try {
            if ($GLOBALS['TSFE']->sys_language_uid > 0) {
                $pages = $this->pagesLanguageOverlayRepository->findLatest($GLOBALS['TSFE']->sys_language_uid, $orderField, $maxResults);

            } else {
                $pages = $this->pagesRepository->findLatest($orderField, $maxResults);
            }

        } catch (\Exception $e) {
            $this->getLogger()->log(\TYPO3\CMS\Core\Log\LogLevel::ERROR, sprintf('An error occurred while trying to fetch relevant pages for RSS feed. Message: %s', str_replace(array("\n", "\r"), '', $e->getMessage())));
        }


        //=============================
        // 2. get contents from pages!
        // check if something is cached!
        $results = null;
        if ($pages) {

            try {
                if (!$results = $this->getCache()->getContent($pages->getFirst()->getUid() . $type)) {

                    $cObj = $this->configurationManager->getContentObject();

                    /** @var \RKW\RkwRss\Domain\Model\Pages $page */
                    foreach ($pages as $page) {

                        // if we have an language overlay we need the Pid instead
                        $pageUid = $page->getUid();
                        if ($GLOBALS['TSFE']->sys_language_uid > 0) {
                            $pageUid = $page->getPid();
                        }

                        // get contents
                        $cConf = array(
                            'table'     => 'tt_content',
                            'select.'   => array(
                                'pidInList'     => $pageUid,
                                'orderBy'       => 'sorting',
                                'where'         => 'colPos = ' . intval($contentColPos), // . ' AND CType IN (\'header\', \'text\', \'textpic\')',
                                'languageField' => 'sys_language_uid',
                            ),
                            'renderObj' => '< plugin.tx_rkwrss.libs.' . $type,
                        );


                        if ($contentHtml = $cObj->render($cObj->getContentObject('CONTENT'), $cConf)) {

                            $pubDate = $page->getCrdate();
                            $getterPubDate = 'get' . \TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($orderField);
                            if (method_exists($page, $getterPubDate)) {
                                $pubDate = $page->$getterPubDate();
                            }

                            $results[] = array(
                                'uid'         => $page->getUid(),
                                'title'       => $page->getTitle(),
                                'subtitle'    => $page->getSubtitle(),
                                'abstract'    => $page->getAbstract() ? $page->getAbstract() : $page->getTxRkwbasicsTeaserText(),
                                'teaserImage' => $page->getTxRkwbasicsTeaserImage(),
                                'tstamp'      => $page->getTstamp(),
                                'crdate'      => $page->getCrdate(),
                                'pubDate'     => $pubDate,
                                'content'     => $contentHtml,
                            );
                        }
                    }


                    // flush caches
                    $this->getCache()->getCacheManager()->flushCachesByTag('rkwrss_contents');

                    // save results in cache
                    $this->getCache()->setContent(
                        $results,
                        array(
                            'rkwrss_contents',
                        )
                    );

                    $this->getLogger()->log(\TYPO3\CMS\Core\Log\LogLevel::INFO, sprintf('Successfully rebuilt RSS feed.'));

                } else {
                    $this->getLogger()->log(\TYPO3\CMS\Core\Log\LogLevel::INFO, sprintf('Successfully loaded RSS feed from cache.'));
                }


            } catch (\Exception $e) {
                $this->getLogger()->log(\TYPO3\CMS\Core\Log\LogLevel::ERROR, sprintf('An error occurred while trying to fetch relevant contents for RSS feed. Message: %s', str_replace(array("\n", "\r"), '', $e->getMessage())));
            }
        }

        // set pubDate based on first content
        if (
            (is_array($results))
            && (is_array($results[0]))
        ) {
            $pubDate = $lastBuildDate = $results[0]['pubDate'];
        }

        if (!$pubDate) {
            $pubDate = $lastBuildDate = time();
        }

        return array(
            'pages'         => $results,
            'pubDate'       => $pubDate,
            'lastBuildDate' => $lastBuildDate,
            'currentDate'   => time(),
            'feedLanguage'  => $GLOBALS['TSFE']->config['config']['htmlTag_langKey'],
            'type'          => $type,
            'settings'      => $this->settings[$type] // override settings with type-specific settings
        );
        //===

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
        //===
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
        //===
    }

}