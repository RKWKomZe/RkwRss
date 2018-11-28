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
 * Class Pages
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwRss
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Pages extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * uid
     *
     * @var integer
     * @validate NotEmpty
     */
    protected $uid;


    /**
     * pid
     *
     * @var integer
     * @validate NotEmpty
     */
    protected $pid;


    /**
     * crdate
     *
     * @var integer
     */
    protected $crdate;


    /**
     * tstamp
     *
     * @var integer
     */
    protected $tstamp;


    /**
     * sorting
     *
     * @var int
     * @validate NotEmpty
     */
    protected $sorting;


    /**
     * title
     *
     * @var string
     */
    protected $title;


    /**
     * subtitle
     *
     * @var string
     */
    protected $subtitle;


    /**
     * abstract
     *
     * @var string
     */
    protected $abstract;


    /**
     * txRkwbasicsTeaserText
     *
     * @var string
     */
    protected $txRkwbasicsTeaserText;


    /**
     * txRkwbasicsTeaserImage
     *
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
     * @validate NotEmpty
     */
    protected $txRkwbasicsTeaserImage = null;


    /**
     * txRkwsearchPubdate
     *
     * @var integer
     */
    protected $txRkwsearchPubdate;


    /**
     * Returns the pid
     *
     * @return int $pid
     */
    public function getPid()
    {
        return $this->pid;
        //===
    }


    /**
     * Returns the sorting
     *
     * @return int $sorting
     */
    public function getSorting()
    {
        return $this->sorting;
        //===
    }


    /**
     * Returns the crdate
     *
     * @return integer $crdate
     */
    public function getCrdate()
    {

        return $this->crdate;
        //===
    }

    /**
     * Returns the tstamp
     *
     * @return integer $tstamp
     */
    public function getTstamp()
    {
        return $this->tstamp;
        //===
    }

    /**
     * Returns the title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
        //===
    }

    /**
     * Returns the subtitle
     *
     * @return string $subtitle
     */
    public function getSubtitle()
    {
        return $this->subtitle;
        //===
    }


    /**
     * Returns the abstract
     *
     * @return string $abstract
     */
    public function getAbstract()
    {
        return $this->abstract;
        //===
    }


    /**
     * Returns the txRkwbasicsTeaserText
     *
     * @return string $txRkwbasicsTeaserText
     */
    public function getTxRkwbasicsTeaserText()
    {
        return $this->txRkwbasicsTeaserText;
    }


    /**
     * Returns the txRkwbasicsTeaserImage
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference $txRkwbasicsTeaserImage
     */
    public function getTxRkwbasicsTeaserImage()
    {
        return $this->txRkwbasicsTeaserImage;
    }


    /**
     * Returns the txRkwsearchPubdate
     *
     * @return integer
     */
    public function getTxRkwsearchPubdate()
    {
        return $this->txRkwsearchPubdate;
    }


}