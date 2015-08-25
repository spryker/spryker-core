<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Cache;

use SprykerEngine\Shared\Kernel\Store;
use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class CacheConfig extends AbstractBundleConfig
{
    public function getCachePath()
    {
        return APPLICATION_ROOT_DIR . '/data/{STORE}/cache';
    }

    public function getAllowedStores()
    {
        return Store::getInstance()->getAllowedStores();
    }
}