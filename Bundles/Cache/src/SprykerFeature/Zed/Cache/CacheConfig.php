<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cache;

use SprykerEngine\Shared\Kernel\Store;
use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

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
