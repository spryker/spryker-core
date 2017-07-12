<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Storage;

use Predis\Client;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Storage\Cache\StorageCacheInactiveStrategy;
use Spryker\Client\Storage\Cache\StorageCacheIncrementalStrategy;
use Spryker\Client\Storage\Cache\StorageCacheReplaceStrategy;
use Spryker\Client\Storage\Exception\InvalidStrategyException;
use Spryker\Client\Storage\Redis\Service;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Storage\StorageConstants;

class StorageFactory extends AbstractFactory
{

    const DEFAULT_REDIS_DATABASE = 0;

    /**
     * @var \Spryker\Client\Storage\StorageClientInterface
     */
    protected static $storageService;

    /**
     * @return \Spryker\Client\Storage\StorageClientInterface
     */
    public function createService()
    {
        return new Service(
            $this->createClient()
        );
    }

    /**
     * @return \Spryker\Client\Storage\StorageClientInterface
     */
    public function createCachedService()
    {
        if (static::$storageService === null) {
            static::$storageService = $this->createService();
        }

        return static::$storageService;
    }

    /**
     * @return \Predis\ClientInterface
     */
    protected function createClient()
    {
        return new Client($this->getConfig());
    }

    /**
     * @deprecated Use getConnectionParameters() instead.
     *
     * @return array
     */
    protected function getConfig()
    {
        return $this->getConnectionParameters();
    }

    /**
     * @return \Spryker\Client\Storage\StorageClientInterface
     *
     * @todo use dependencyProvider and call it get
     */
    protected function createStorageClient()
    {
        return new StorageClient();
    }

    /**
     * @param string $storageCacheStrategy
     *
     * @throws \Spryker\Client\Storage\Exception\InvalidStrategyException
     *
     * @return \Spryker\Client\Storage\Cache\StorageCacheStrategyInterface
     *
     * @todo move to strategyBuilder
     */
    public function createStorageCacheStrategy($storageCacheStrategy)
    {
        $cacheStrategyStack = $this->createCacheStrategyStack();

        if (!isset($cacheStrategyStack[$storageCacheStrategy])) {
            throw new InvalidStrategyException($storageCacheStrategy . " is not a valid storage cache strategy.");
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
            $this->createStorageClient()
        );
    }

    /**
     * @return \Spryker\Client\Storage\Cache\StorageCacheIncrementalStrategy
     */
    protected function createCacheIncrementalStrategy()
    {
        return new StorageCacheIncrementalStrategy(
            $this->createStorageClient()
        );
    }

    /**
     * @return \Spryker\Client\Storage\Cache\StorageCacheInactiveStrategy
     */
    protected function createCacheInactiveStrategy()
    {
        return new StorageCacheInactiveStrategy(
            $this->createStorageClient()
        );
    }

    /**
     * @return array
     */
    protected function getConnectionParameters()
    {
        $config = [
            'protocol' => Config::get(StorageConstants::STORAGE_REDIS_PROTOCOL),
            'port' => Config::get(StorageConstants::STORAGE_REDIS_PORT),
            'host' => Config::get(StorageConstants::STORAGE_REDIS_HOST),
            'database' => Config::get(StorageConstants::STORAGE_REDIS_DATABASE, static::DEFAULT_REDIS_DATABASE),
        ];

        if (Config::hasKey(StorageConstants::STORAGE_REDIS_PASSWORD)) {
            $config['password'] = Config::get(StorageConstants::STORAGE_REDIS_PASSWORD);
        }

        $config['persistent'] = false;
        if (Config::hasKey(StorageConstants::STORAGE_PERSISTENT_CONNECTION)) {
            $config['persistent'] = (bool)Config::get(StorageConstants::STORAGE_PERSISTENT_CONNECTION);
        }

        return $config;
    }

}
