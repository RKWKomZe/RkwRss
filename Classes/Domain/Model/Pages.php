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
class Pages extends \RKW\RkwBasics\Domain\Model\Pages implements PagesInterface
{

    /**
     * Contents
     *
     * @var \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    protected $contents = [];


    /**
     * Gets the contents
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface $contents
     */
    public function getContents()
    {
        return $this->contents;
    }


    /**
     * Sets the contents
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryResultInterface $contents
     * @return void
     */
    public function setContents($contents)
    {
        $this->contents = $contents;
    }


    /**
     * Returns the publicationTime
     *
     * @return integer
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    public function getPublicationTime()
    {
        $settings = $this->getSettings();
        $getter = 'get' . \TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($settings['global']['orderField']);
        if (method_exists($this, $getter)) {
            return $this->$getter();
        }
        return ($this->getLastUpdated() ? $this->getLastUpdated() : $this->getCrdate());

    }


    /**
     * Returns TYPO3 settings
     *
     * @param string $which Which type of settings will be loaded
     * @return array
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    protected function getSettings(string $which = \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS)
    {
        return \RKW\RkwBasics\Utility\GeneralUtility::getTyposcriptConfiguration('RkwRss', $which);
    }
}
