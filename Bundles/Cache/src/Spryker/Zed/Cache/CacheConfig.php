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
    /**
     * @var string
     */
    public const STORE_PATTERN_MARKER = '{STORE}';

    /**
     * @api
     *
     * @deprecated Use {@link getCodeBucketCachePath()} instead.
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
        return sprintf(APPLICATION_ROOT_DIR . '/src/Generated/*/*/codeBucket%s', APPLICATION_CODE_BUCKET);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getDefaultCodeBucketCachePath(): string
    {
        return APPLICATION_ROOT_DIR . '/src/Generated/*/*/codeBucket';
    }

    /**
     * @api
     *
     * @deprecated Will be removed without replacement.
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
    public function getStorePatternMarker()
    {
        return static::STORE_PATTERN_MARKER;
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getAllowedStores()
    {
        return Store::getInstance()->getAllowedStores();
    }

    /**
     * Specification:
     * - Defines project specific cache paths that should be cleared.
     *
     * @api
     *
     * @return array<string>
     */
    public function getProjectSpecificCache(): array
    {
        return [];
    }
}
