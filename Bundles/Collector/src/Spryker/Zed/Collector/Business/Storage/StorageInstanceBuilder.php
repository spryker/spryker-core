<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Storage;

use Elastica\Client;
use ErrorException;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Search\SearchConstants;
use Spryker\Shared\Storage\StorageConstants;

class StorageInstanceBuilder
{
    public const KV_NAMESPACE = 'Spryker\\Zed\\Collector\\Business\\Storage\\Adapter\\KeyValue\\';
    public const ADAPTER_READ_WRITE = 'ReadWrite';
    public const ADAPTER_READ = 'Read';

    public const KV_ADAPTER_REDIS = 'redis';
    public const SEARCH_ELASTICA_ADAPTER = 'elastica';

    public const DEFAULT_REDIS_DATABASE = 0;

    /**
     * @var \Spryker\Zed\Collector\Business\Storage\Adapter\KeyValue\ReadInterface[]|\Spryker\Zed\Collector\Business\Storage\Adapter\KeyValue\ReadWriteInterface[]
     */
    protected static $storageInstances = [];

    /**
     * @var array
     */
    protected static $searchInstances = [];

    /**
     * @return \Elastica\Client
     */
    public static function getElasticsearchInstance()
    {
        $adapterName = static::SEARCH_ELASTICA_ADAPTER;

        if (array_key_exists($adapterName, static::$searchInstances) === false) {
            $config = static::getElasticsearchClientConfig();

            static::$searchInstances[$adapterName] = new Client($config);
        }

        return static::$searchInstances[$adapterName];
    }

    /**
     * @param bool $debug
     *
     * @return \Spryker\Zed\Collector\Business\Storage\Adapter\KeyValue\ReadWriteInterface
     */
    public static function getStorageReadWriteInstance($debug = false)
    {
        /** @var \Spryker\Zed\Collector\Business\Storage\Adapter\KeyValue\ReadWriteInterface $interface */
        $interface = static::getStorageInstance(static::ADAPTER_READ_WRITE, $debug);

        return $interface;
    }

    /**
     * @param bool $debug
     *
     * @return \Spryker\Zed\Collector\Business\Storage\Adapter\KeyValue\ReadInterface
     */
    public static function getStorageReadInstance($debug = false)
    {
        return static::getStorageInstance(static::ADAPTER_READ, $debug);
    }

    /**
     * @param string $type
     * @param bool $debug
     *
     * @return \Spryker\Zed\Collector\Business\Storage\Adapter\KeyValue\ReadInterface|\Spryker\Zed\Collector\Business\Storage\Adapter\KeyValue\ReadWriteInterface
     */
    protected static function getStorageInstance($type, $debug = false)
    {
        $kvAdapter = Config::get(StorageConstants::STORAGE_KV_SOURCE);

        $storageAdapter = static::createStorageAdapterName($type, $kvAdapter);
        $configArray = static::createAdapterConfig($kvAdapter);
        $options = static::getAdapterOptions();

        $storage = new $storageAdapter($configArray, $options, $debug);
        static::$storageInstances[$storageAdapter] = $storage;

        return static::$storageInstances[$storageAdapter];
    }

    /**
     * @param string $kvAdapter
     *
     * @throws \ErrorException
     *
     * @return array
     */
    protected static function createAdapterConfig($kvAdapter)
    {
        $config = null;

        switch ($kvAdapter) {
            case static::KV_ADAPTER_REDIS:
                $config = static::getRedisClientConfig();
                break;

            case static::SEARCH_ELASTICA_ADAPTER:
                $config = static::getElasticsearchClientConfig();
                break;
        }

        if ($config === null) {
            throw new ErrorException('Missing implementation for adapter ' . $kvAdapter);
        }

        return $config;
    }

    /**
     * @param string $type
     * @param string $kvAdapter
     *
     * @return string
     */
    protected static function createStorageAdapterName($type, $kvAdapter)
    {
        $storageAdapter = static::KV_NAMESPACE . ucfirst(strtolower($kvAdapter)) . $type;

        return $storageAdapter;
    }

    /**
     * @return array
     */
    protected static function getRedisClientConfig()
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
     * @return array
     */
    protected static function getElasticsearchClientConfig()
    {
        if (Config::hasValue(SearchConstants::ELASTICA_CLIENT_CONFIGURATION)) {
            return Config::get(SearchConstants::ELASTICA_CLIENT_CONFIGURATION);
        }

        if (Config::hasValue(SearchConstants::ELASTICA_PARAMETER__EXTRA)) {
            $config = Config::get(SearchConstants::ELASTICA_PARAMETER__EXTRA);
        }

        $config['protocol'] = ucfirst(Config::get(SearchConstants::ELASTICA_PARAMETER__TRANSPORT));
        $config['port'] = Config::get(SearchConstants::ELASTICA_PARAMETER__PORT);
        $config['host'] = Config::get(SearchConstants::ELASTICA_PARAMETER__HOST);

        if (Config::hasValue(SearchConstants::ELASTICA_PARAMETER__AUTH_HEADER)) {
            $config['headers'] = [
                'Authorization' => 'Basic ' . Config::get(SearchConstants::ELASTICA_PARAMETER__AUTH_HEADER),
            ];
        }

        return $config;
    }

    /**
     * @return mixed|null
     */
    protected static function getAdapterOptions()
    {
        if (Config::hasKey(StorageConstants::STORAGE_PREDIS_CLIENT_OPTIONS)) {
            return Config::get(StorageConstants::STORAGE_PREDIS_CLIENT_OPTIONS);
        }

        return null;
    }
}
