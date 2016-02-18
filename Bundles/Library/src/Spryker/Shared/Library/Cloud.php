<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library;

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
        static::$cloudConfigEnabled = Config::get(LibraryConstants::CLOUD_ENABLED);
    }

    /**
     * @return bool
     */
    public static function isCloudStorageEnabled()
    {
        if (!static::isCloudEnabled()) {
            return false;
        }

        return Config::get(LibraryConstants::CLOUD_OBJECT_STORAGE_ENABLED) === true;
    }

    /**
     * @return bool
     */
    public static function isCloudStorageCdnEnabled()
    {
        if (!static::isCloudStorageEnabled()) {
            return false;
        }

        return Config::get(LibraryConstants::CLOUD_CDN_ENABLED) === true;
    }

}
