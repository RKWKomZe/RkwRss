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

use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/**
 * Interface Pages
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwRss
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
interface PagesInterface
{

    /**
     * Gets the contents
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface|null $contents
     */
    public function getContents(): ?QueryResultInterface;


    /**
     * Sets the contents
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryResultInterface $contents
     * @return void
     */
    public function setContents(QueryResultInterface $contents);


    /**
     * Returns the publicationTime
     *
     * @return int
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    public function getPublicationTime(): int;

}
