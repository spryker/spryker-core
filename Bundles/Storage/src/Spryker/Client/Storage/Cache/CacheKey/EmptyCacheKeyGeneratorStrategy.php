<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Storage\Cache\CacheKey;

use Spryker\Client\Storage\StorageConfig;
use Symfony\Component\HttpFoundation\Request;

class EmptyCacheKeyGeneratorStrategy implements CacheKeyGeneratorStrategyInterface
{
    /**
     * @var \Spryker\Client\Storage\StorageConfig
     */
    protected $config;

    /**
     * @param \Spryker\Client\Storage\StorageConfig $config
     */
    public function __construct(StorageConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request|null $request
     *
     * @return string
     */
    public function generateCacheKey(?Request $request = null): string
    {
        return '';
    }

    /**
     * @return bool
     */
    public function isApplicable(): bool
    {
        return !$this->config->isStorageCachingEnabled();
    }
}
