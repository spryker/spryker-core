<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Storage\Cache;

use Spryker\Client\Storage\Exception\InvalidStrategyException;
use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Client\Storage\StorageConfig;
use Spryker\Shared\Storage\StorageConstants;

class StorageCacheStrategyFactory
{
    /**
     * @var \Spryker\Client\Storage\StorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\Storage\StorageConfig
     */
    protected $storageClientConfig;

    /**
     * @param \Spryker\Client\Storage\StorageClientInterface $storageClient
     * @param \Spryker\Client\Storage\StorageConfig $storageConfig
     */
    public function __construct(
        StorageClientInterface $storageClient,
        StorageConfig $storageConfig
    ) {

        $this->storageClient = $storageClient;
        $this->storageClientConfig = $storageConfig;
    }

    /**
     * @param string $storageCacheStrategy
     *
     * @throws \Spryker\Client\Storage\Exception\InvalidStrategyException
     *
     * @return \Spryker\Client\Storage\Cache\StorageCacheStrategyInterface
     */
    public function createStorageCacheStrategy($storageCacheStrategy)
    {
        $cacheStrategyStack = $this->createCacheStrategyStack();

        if (!isset($cacheStrategyStack[$storageCacheStrategy])) {
            throw new InvalidStrategyException($storageCacheStrategy . ' is not a valid storage cache strategy.');
        }

        return $cacheStrategyStack[$storageCacheStrategy];
    }

    /**
     * @return \Spryker\Client\Storage\Cache\StorageCacheStrategyInterface[]
     */
    protected function createCacheStrategyStack()
    {
        return [
            StorageConstants::STORAGE_CACHE_STRATEGY_REPLACE => $this->createCacheReplaceStrategy(),
            StorageConstants::STORAGE_CACHE_STRATEGY_INCREMENTAL => $this->createCacheIncrementalStrategy(),
            StorageConstants::STORAGE_CACHE_STRATEGY_INACTIVE => $this->createCacheInactiveStrategy(),
        ];
    }

    /**
     * @return \Spryker\Client\Storage\Cache\StorageCacheReplaceStrategy
     */
    protected function createCacheReplaceStrategy()
    {
        return new StorageCacheReplaceStrategy(
            $this->createStorageCacheStrategyHelper()
        );
    }

    /**
     * @return \Spryker\Client\Storage\Cache\StorageCacheIncrementalStrategy
     */
    protected function createCacheIncrementalStrategy()
    {
        return new StorageCacheIncrementalStrategy(
            $this->createStorageCacheStrategyHelper(),
            $this->storageClientConfig
        );
    }

    /**
     * @return \Spryker\Client\Storage\Cache\StorageCacheInactiveStrategy
     */
    protected function createCacheInactiveStrategy()
    {
        return new StorageCacheInactiveStrategy();
    }

    /**
     * @return \Spryker\Client\Storage\Cache\StorageCacheStrategyHelper
     */
    protected function createStorageCacheStrategyHelper()
    {
        return new StorageCacheStrategyHelper(
            $this->storageClient,
            $this->storageClientConfig
        );
    }
}
