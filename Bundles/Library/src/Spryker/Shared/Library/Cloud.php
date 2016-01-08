<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Library;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config;

class Cloud
{

    /**
     * @var bool
     */
    protected static $cloudConfigEnabled;

    /**
     * @return bool
     */
    public static function isCloudEnabled()
    {
        if (static::$cloudConfigEnabled === null) {
            static::init();
        }

        if (static::$cloudConfigEnabled) {
            return static::$cloudConfigEnabled === true;
        } else {
            return false;
        }
    }

    /**
     * @return void
     */
    public static function init()
    {
        static::$cloudConfigEnabled = Config::get(ApplicationConstants::CLOUD_ENABLED);
    }

    /**
     * @return bool
     */
    public static function isCloudStorageEnabled()
    {
        if (!static::isCloudEnabled()) {
            return false;
        }

        return Config::get(ApplicationConstants::CLOUD_OBJECT_STORAGE_ENABLED) === true;
    }

    /**
     * @return bool
     */
    public static function isCloudStorageCdnEnabled()
    {
        if (!static::isCloudStorageEnabled()) {
            return false;
        }

        return Config::get(ApplicationConstants::CLOUD_CDN_ENABLED) === true;
    }

}
