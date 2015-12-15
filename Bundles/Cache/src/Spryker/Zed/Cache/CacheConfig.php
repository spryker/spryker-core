<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Cache;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class CacheConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getCachePath()
    {
        return APPLICATION_ROOT_DIR . '/data/{STORE}/cache';
    }

    /**
     * @return array
     */
    public function getAllowedStores()
    {
        return Store::getInstance()->getAllowedStores();
    }

}
