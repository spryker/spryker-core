<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Storage;

use Predis\Client;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Storage\Cache\Key\CacheKeyStrategyInterface;
use Spryker\Client\Storage\Cache\Key\CacheKeyStrategyProviderInterface;
use Spryker\Client\Storage\Cache\Key\ConfigCacheKeyStrategyProvider;
use Spryker\Client\Storage\Cache\Key\EmptyCacheKeyStrategy;
use Spryker\Client\Storage\Cache\Key\RequestCacheKeyStrategy;
use Spryker\Client\Storage\Cache\StorageCacheStrategyFactory;
use Spryker\Client\Storage\Dependency\Client\StorageToLocaleClientInterface;
use Spryker\Client\Storage\Dependency\Client\StorageToStoreClientInterface;
use Spryker\Client\Storage\Redis\Service;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Storage\StorageConstants;

class StorageFactory extends AbstractFactory
{
    public const DEFAULT_REDIS_DATABASE = 0;

    /**
     * @var \Spryker\Client\Storage\Redis\ServiceInterface
     */
    protected static $storageService;

    /**
     * @return \Spryker\Client\Storage\Redis\ServiceInterface
     */
    public function createService()
    {
        return new Service(
            $this->createClient()
        );
    }

    /**
     * @return \Spryker\Client\Storage\Redis\ServiceInterface
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
        return new Client($this->getConfig(), $this->getPredisClientOptions());
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
     * @return \Spryker\Client\Storage\StorageConfig
     */
    public function getStorageClientConfig()
    {
        /** @var \Spryker\Client\Storage\StorageConfig $config */
        $config = parent::getConfig();

        return $config;
    }

    /**
     * @return \Spryker\Client\Storage\StorageClientInterface
     */
    protected function getStorageClient()
    {
        return $this->getProvidedDependency(StorageDependencyProvider::STORAGE_CLIENT);
    }

    /**
     * @return \Spryker\Client\Storage\Dependency\Client\StorageToStoreClientInterface
     */
    public function getStoreClient(): StorageToStoreClientInterface
    {
        return $this->getProvidedDependency(StorageDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Client\Storage\Dependency\Client\StorageToLocaleClientInterface
     */
    public function getLocaleClient(): StorageToLocaleClientInterface
    {
        return $this->getProvidedDependency(StorageDependencyProvider::CLIENT_LOCALE);
    }

    /**
     * @return \Spryker\Client\Storage\Cache\StorageCacheStrategyFactory
     */
    protected function createStorageClientStrategyFactory()
    {
        return new StorageCacheStrategyFactory(
            $this->getStorageClient(),
            $this->getStorageClientConfig()
        );
    }

    /**
     * @param string $storageCacheStrategy
     *
     * @return Cache\StorageCacheStrategyInterface
     */
    public function createStorageCacheStrategy($storageCacheStrategy)
    {
        return $this
            ->createStorageClientStrategyFactory()
            ->createStorageCacheStrategy($storageCacheStrategy);
    }

    /**
     * @return \Spryker\Client\Storage\Cache\Key\CacheKeyStrategyProviderInterface
     */
    public function createCacheKeyGenerationStrategyProvider(): CacheKeyStrategyProviderInterface
    {
        return new ConfigCacheKeyStrategyProvider(
            $this->createCacheKeyGenerationStrategyStack(),
            $this->getStorageClientConfig()
        );
    }

    /**
     * @return \Spryker\Client\Storage\Cache\Key\CacheKeyStrategyInterface[]
     */
    public function createCacheKeyGenerationStrategyStack(): array
    {
        return [
            $this->createRequestCacheKeyGenerationStrategy(),
            $this->createEmptyCacheKeyGenerationStrategy(),
        ];
    }

    /**
     * @return \Spryker\Client\Storage\Cache\Key\CacheKeyStrategyInterface
     */
    public function createRequestCacheKeyGenerationStrategy(): CacheKeyStrategyInterface
    {
        return new RequestCacheKeyStrategy(
            $this->getStoreClient(),
            $this->getLocaleClient(),
            $this->getStorageClientConfig()
        );
    }

    /**
     * @return \Spryker\Client\Storage\Cache\Key\CacheKeyStrategyInterface
     */
    public function createEmptyCacheKeyGenerationStrategy(): CacheKeyStrategyInterface
    {
        return new EmptyCacheKeyStrategy(
            $this->getStorageClientConfig()
        );
    }

    /**
     * @return array
     */
    protected function getConnectionParameters()
    {
        if (Config::hasKey(StorageConstants::STORAGE_PREDIS_CLIENT_CONFIGURATION)) {
            return Config::get(StorageConstants::STORAGE_PREDIS_CLIENT_CONFIGURATION);
        }

        $config = [
            'protocol' => Config::get(StorageConstants::STORAGE_REDIS_PROTOCOL),
            'port' => Config::get(StorageConstants::STORAGE_REDIS_PORT),
            'host' => Config::get(StorageConstants::STORAGE_REDIS_HOST),
            'database' => Config::get(StorageConstants::STORAGE_REDIS_DATABASE, static::DEFAULT_REDIS_DATABASE),
        ];

        $password = Config::get(StorageConstants::STORAGE_REDIS_PASSWORD, false);
        if ($password !== false) {
            $config['password'] = $password;
        }

        $config['persistent'] = false;
        if (Config::hasKey(StorageConstants::STORAGE_PERSISTENT_CONNECTION)) {
            $config['persistent'] = (bool)Config::get(StorageConstants::STORAGE_PERSISTENT_CONNECTION);
        }

        return $config;
    }

    /**
     * @return mixed|null
     */
    protected function getPredisClientOptions()
    {
        if (Config::hasKey(StorageConstants::STORAGE_PREDIS_CLIENT_OPTIONS)) {
            return Config::get(StorageConstants::STORAGE_PREDIS_CLIENT_OPTIONS);
        }

        return null;
    }
}
