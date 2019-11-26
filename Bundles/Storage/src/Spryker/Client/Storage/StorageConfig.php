<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Storage;

use Spryker\Client\Kernel\AbstractBundleConfig;

class StorageConfig extends AbstractBundleConfig
{
    protected const STORAGE_CACHE_ENABLED = true;

    /**
     * @return int
     */
    public function getStorageCacheIncrementalStrategyKeySizeLimit()
    {
        return 1000;
    }

    /**
     * @return int
     */
    public function getStorageCacheTtl()
    {
        return 86400;
    }

    /**
     * Specification:
     * - Defines parameter names which will be used for kv multi get optimisation.
     * - Please make sure you use an expected case for parameter names.
     *
     * @return string[]
     */
    public function getAllowedGetParametersList(): array
    {
        return [];
    }

    /**
     * @return bool
     */
    public function isStorageCachingEnabled(): bool
    {
        return static::STORAGE_CACHE_ENABLED;
    }
}
