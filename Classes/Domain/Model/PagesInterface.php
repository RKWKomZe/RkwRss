<?php
namespace RKW\RkwRss\Domain\Model;

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
 * Interface Pages
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwRss
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
interface PagesInterface
{


    /**
     * Gets the contents
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface $contents
     */
    public function getContents();


    /**
     * Sets the contents
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryResultInterface $contents
     * @return void
     */
    public function setContents($contents);

    /**
     * Returns the publicationTime
     *
     * @return integer
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    public function getPublicationTime();

}