<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Touch\Persistence\Propel;

use SprykerEngine\Zed\Touch\Persistence\Propel\Base\SpyTouchQuery as BaseSpyTouchQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'spy_touch' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class SpyTouchQuery extends BaseSpyTouchQuery
{
    /**
     * @var array
     */
    protected static $cache = [];

    public function cacheContains($key)
    {
        return isset(self::$cache[$key]);
    }

    public function cacheFetch($key)
    {
        return self::$cache[$key];
    }

    public function cacheStore($key, $value, $lifetime = 3600)
    {
        self::$cache[$key] = $value;
    }
} // SpyTouchQuery
