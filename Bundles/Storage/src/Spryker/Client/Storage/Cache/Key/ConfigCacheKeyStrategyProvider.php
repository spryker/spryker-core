<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Storage\Cache\Key;

use Spryker\Client\Storage\Exception\InvalidCacheKeyGenerationStrategyException;
use Spryker\Client\Storage\StorageConfig;

class ConfigCacheKeyStrategyProvider implements CacheKeyStrategyProviderInterface
{
    /**
     * @var \Spryker\Client\Storage\Cache\Key\CacheKeyStrategyInterface[]
     */
    protected $cacheKeyGenerationStrategyStack;

    /**
     * @var \Spryker\Client\Storage\StorageConfig
     */
    protected $config;

    /**
     * @param \Spryker\Client\Storage\Cache\Key\CacheKeyStrategyInterface[] $cacheKeyGenerationStrategyStack
     * @param \Spryker\Client\Storage\StorageConfig $config
     */
    public function __construct(array $cacheKeyGenerationStrategyStack, StorageConfig $config)
    {
        $this->cacheKeyGenerationStrategyStack = $cacheKeyGenerationStrategyStack;
        $this->config = $config;
    }

    /**
     * @return \Spryker\Client\Storage\Cache\Key\CacheKeyStrategyInterface
     */
    public function provideCacheKeyGenerationStrategy(): CacheKeyStrategyInterface
    {
        if (!$this->config->isStorageCachingEnabled()) {
            return $this->getEmptyCacheKeyGenerationStrategy();
        }

        return $this->getDefaultCacheKeyGenerationStrategy();
    }

    /**
     * @return \Spryker\Client\Storage\Cache\Key\CacheKeyStrategyInterface
     */
    protected function getEmptyCacheKeyGenerationStrategy(): CacheKeyStrategyInterface
    {
        return $this->getCacheKeyGenerationStrategy(
            $this->config->getEmptyCacheKeyGenerationStrategy()
        );
    }

    /**
     * @return \Spryker\Client\Storage\Cache\Key\CacheKeyStrategyInterface
     */
    protected function getDefaultCacheKeyGenerationStrategy(): CacheKeyStrategyInterface
    {
        return $this->getCacheKeyGenerationStrategy(
            $this->config->getDefaultCacheKeyGenerationStrategy()
        );
    }

    /**
     * @param string $strategyName
     *
     * @throws \Spryker\Client\Storage\Exception\InvalidCacheKeyGenerationStrategyException
     *
     * @return \Spryker\Client\Storage\Cache\Key\CacheKeyStrategyInterface
     */
    protected function getCacheKeyGenerationStrategy(string $strategyName): CacheKeyStrategyInterface
    {
        foreach ($this->cacheKeyGenerationStrategyStack as $cacheKeyGenerationStrategy) {
            if ($cacheKeyGenerationStrategy->getStrategyName() === $strategyName) {
                return $cacheKeyGenerationStrategy;
            }
        }

        throw new InvalidCacheKeyGenerationStrategyException(
            sprintf('%s is not a valid storage cache strategy.', $strategyName)
        );
    }
}
