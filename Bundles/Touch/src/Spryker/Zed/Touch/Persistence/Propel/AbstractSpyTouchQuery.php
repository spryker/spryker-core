<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Touch\Persistence\Propel;

use Orm\Zed\Touch\Persistence\Base\SpyTouchQuery as BaseSpyTouchQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'spy_touch' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements. This class will only be generated as
 * long as it does not already exist in the output directory.
 */
abstract class AbstractSpyTouchQuery extends BaseSpyTouchQuery
{
    /**
     * @var array
     */
    protected static $cache = [];

    /**
     * @param string $key
     *
     * @return bool
     */
    public function cacheContains($key)
    {
        return array_key_exists($key, self::$cache);
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function cacheFetch($key)
    {
        return self::$cache[$key];
    }

    /**
     * @param string $key
     * @param string $value
     * @param int $lifetime
     *
     * @return void
     */
    public function cacheStore($key, $value, $lifetime = 3600)
    {
        self::$cache[$key] = $value;
    }
}
