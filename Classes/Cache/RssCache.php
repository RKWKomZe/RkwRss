<?php

namespace RKW\RkwRss\Cache;

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
 * Class RssCache
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwRss
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class RssCache extends \RKW\RkwBasics\Cache\CacheAbstract
{
    /**
     * @var string Key for cache
     */
    protected $_key = 'rkw_rss';

    /**
     * @var string Identifier for cache
     */
    protected $_identifier = 'rkw_rss';


}