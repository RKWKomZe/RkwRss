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

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * TtContentRepository
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_Rss
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class TtContentRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    public function initializeObject()
    {
        $this->defaultQuerySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
        $this->defaultQuerySettings->setRespectStoragePage(false);
    }


    /**
     * Find all by colPos
     *
     * @param int $pageUid
     * @param string $colPosString Table and column id of content element
     * @param int $languageUid LanguageUid for query
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findAllByColumn($pageUid, $colPosString = 'colpos_0', $languageUid = 0)
    {

        $query = $this->createQuery();
        $query->setOrderings(
            array(
                'sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING,
            )
        );

        $colPosField = 'colpos';
        $colPosValue = intval($colPosString);

        if (strpos($colPosString, '_')) {
            $colArray = explode ('_', $colPosString);

            /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper $dataMapper */
            $dataMapper = $this->objectManager->get(\TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper::class);
            if ($dataMapper->isPersistableProperty('RKW\\RkwRss\\Domain\\Model\\TtContent', $colArray[0])) {
                $colPosField = $colArray[0];
                $colPosValue = intval($colArray[1]);
            }
        }

        return $query->matching(
            $query->logicalAnd(
                $query->equals('pid', intval($pageUid)),
                $query->equals('sysLanguageUid', intval($languageUid)),
                $query->equals($colPosField, intval($colPosValue))
            )
        )->execute();
    }


}
