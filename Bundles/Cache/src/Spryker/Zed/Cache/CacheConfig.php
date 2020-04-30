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
     * @api
     *
     * @deprecated Use getCodeBucketCachePath() instead.
     *
     * @return string
     */
    public function getCachePath()
    {
        return APPLICATION_ROOT_DIR . '/data/' . static::STORE_PATTERN_MARKER . '/cache';
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCodeBucketCachePath(): string
    {
        return sprintf(APPLICATION_ROOT_DIR . '/data/cache/codeBucket%s', APPLICATION_CODE_BUCKET);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getDefaultCodeBucketCachePath(): string
    {
        return APPLICATION_ROOT_DIR . '/data/cache/codeBucket';
    }

    /**
     * @api
     *
     * @deprecated Use getCodeBucketAutoloaderCachePath() instead.
     *
     * @return string
     */
    public function getAutoloaderCachePath()
    {
        return APPLICATION_ROOT_DIR . '/data/' . static::STORE_PATTERN_MARKER . '/autoloader';
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCodeBucketAutoloaderCachePath(): string
    {
        return APPLICATION_ROOT_DIR . '/data/autoloader';
    }

    /**
     * @api
     *
     * @return string
     */
    public function getStorePatternMarker()
    {
        return static::STORE_PATTERN_MARKER;
    }

    /**
     * @api
     *
     * @return array
     */
    public function getAllowedStores()
    {
        return Store::getInstance()->getAllowedStores();
    }
}
