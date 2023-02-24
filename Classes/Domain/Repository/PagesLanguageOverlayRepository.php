<?php

namespace RKW\RkwRss\Domain\Repository;

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

use TYPO3\CMS\Core\Database\QueryGenerator;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/**
 * Class PagesLanguageOverlayRepository
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwRss
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class PagesLanguageOverlayRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * initializeObject
     *
     * @return void
     */
    public function initializeObject(): void
    {

        /** @var $querySettings \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings */
        $querySettings = $this->objectManager->get(Typo3QuerySettings::class);

        // don't add the pid constraint
        $querySettings->setRespectStoragePage(false);
        $querySettings->setRespectSysLanguage(false);

        $this->setDefaultQuerySettings($querySettings);
    }


    /**
     * Find the latest pages
     *
     * @param int $rootPid
     * @param int $languageUid LanguageUid for query
     * @param string $field Field to order by
     * @param int $limit Number of items to fetch
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function findLatest(
        int $rootPid,
        int $languageUid,
        string $field = 'crdate',
        int $limit = 100
    ): QueryResultInterface {

        $query = $this->createQuery();
        $query->setOrderings(
            array(
                $field => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING,
            )
        );

        return $query->matching(
                $query->logicalAnd(
                    $query->equals('sysLanguageUid', intval($languageUid)),
                    $query->in('pid', $this->getPidList($rootPid))
                )
            )->setLimit(intval($limit))
            ->execute();
    }


    /**
     * Get all subpages of given PIDs
     *
     * @param int $rootPid
     * @param int $depth
     * @return array
     */
    protected function getPidList(int $rootPid = 0, int $depth = 999999): array
    {

        /** @var \TYPO3\CMS\Core\Database\QueryGenerator $queryGenerator */
        $queryGenerator = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(QueryGenerator::class);
        return explode(',', $queryGenerator->getTreeList($rootPid, $depth, 0, 1));
    }
}
