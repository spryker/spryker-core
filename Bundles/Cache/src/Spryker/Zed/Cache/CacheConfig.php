<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cache;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class CacheConfig extends AbstractBundleConfig
{
    public const STORE_PATTERN_MARKER = '{STORE}';

    /**
     * @return string
     */
    public function getCachePath()
    {
        return APPLICATION_ROOT_DIR . '/data/' . static::STORE_PATTERN_MARKER . '/cache';
    }

    /**
     * @return string
     */
    public function getAutoloaderCachePath()
    {
        return APPLICATION_ROOT_DIR . '/data/' . static::STORE_PATTERN_MARKER . '/autoloader';
    }

    /**
     * @return string
     */
    public function getStorePatternMarker()
    {
        return static::STORE_PATTERN_MARKER;
    }

    /**
     * @return array
     */
    public function getAllowedStores()
    {
        return Store::getInstance()->getAllowedStores();
    }
}
