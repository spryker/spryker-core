<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Storage;

use Predis\Client;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Storage\Cache\CacheKey\CacheKeyGenerator;
use Spryker\Client\Storage\Cache\CacheKey\CacheKeyGeneratorInterface;
use Spryker\Client\Storage\Cache\StorageCacheStrategyFactory;
use Spryker\Client\Storage\Dependency\Client\StorageToLocaleClientInterface;
use Spryker\Client\Storage\Dependency\Client\StorageToStoreClientInterface;
use Spryker\Client\Storage\Redis\Service;
use Spryker\Client\StorageExtension\Dependency\Plugin\StoragePluginInterface;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Storage\StorageConstants;

class StorageFactory extends AbstractFactory
{
    /**
     * @deprecated Will be removed with next major release. StorageRedis module handles default database value.
     */
    public const DEFAULT_REDIS_DATABASE = 0;

    /**
     * @var \Spryker\Client\Storage\Redis\ServiceInterface|\Spryker\Client\StorageExtension\Dependency\Plugin\StoragePluginInterface
     */
    protected static $storageService;

    /**
     * @deprecated Use `Spryker\Client\StorageExtension\Dependency\Plugin\StoragePluginInterface` instead.
     *
     * @return \Spryker\Client\Storage\Redis\ServiceInterface
     */
    public function createService()
    {
        return new Service(
            $this->createClient()
        );
    }

    /**
     * @return \Spryker\Client\Storage\Redis\ServiceInterface|\Spryker\Client\StorageExtension\Dependency\Plugin\StoragePluginInterface
     */
    public function createCachedService()
    {
        if (static::$storageService === null) {
            /**
             * This check was added for BC only and will be removed with the next major release.
             */
            static::$storageService = $this->getStoragePlugin() ?? $this->createService();
        }

        return static::$storageService;
    }

    /**
     * @deprecated Will be removed with next major release. Use storage plugins instead.
     *
     * @return \Predis\ClientInterface
     */
    protected function createClient()
    {
        return new Client($this->getPredisConfig(), $this->getPredisClientOptions());
    }

    /**
     * @deprecated Use getConnectionParameters() instead.
     *
     * @return array
     */
    protected function getPredisConfig()
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
     * @return \Spryker\Client\StorageExtension\Dependency\Plugin\StoragePluginInterface|null
     */
    protected function getStoragePlugin(): ?StoragePluginInterface
    {
        return $this->getProvidedDependency(StorageDependencyProvider::PLUGIN_STORAGE);
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
     * @return \Spryker\Client\Storage\Cache\StorageCacheStrategyInterface
     */
    public function createStorageCacheStrategy($storageCacheStrategy)
    {
        return $this
            ->createStorageClientStrategyFactory()
            ->createStorageCacheStrategy($storageCacheStrategy);
    }

    /**
     * @return \Spryker\Client\Storage\Cache\CacheKey\CacheKeyGeneratorInterface
     */
    public function createCacheKeyGenerator(): CacheKeyGeneratorInterface
    {
        return new CacheKeyGenerator(
            $this->getStoreClient(),
            $this->getLocaleClient(),
            $this->getStorageClientConfig()
        );
    }

    /**
     * @deprecated Will be removed with next major release. Use storage plugins instead.
     *
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
     * @deprecated Will be removed with next major release. Use storage plugins instead.
     *
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
