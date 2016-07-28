<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\Storage;

use Elastica\Client;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Library\LibraryConstants;
use Spryker\Shared\Storage\StorageConstants;

class StorageInstanceBuilder
{

    const KV_NAMESPACE = '\Spryker\Shared\Library\Storage\Adapter\KeyValue\\';
    const SEARCH_ELASTICA_ADAPTER = 'elastica';
    const ADAPTER_READ_WRITE = 'ReadWrite';
    const ADAPTER_READ = 'Read';
    const ADAPTER_LOCAL = 'Local';
    const KV_ADAPTER_REDIS = 'redis';
    const DEFAULT_DATABASE = 0;

    /**
     * @var \Spryker\Shared\Library\Storage\AdapterInterface[]
     */
    protected static $storageInstances = [];

    /**
     * @var array
     */
    protected static $searchInstances = [];

    /**
     * @throws \ErrorException
     *
     * @return \Elastica\Client
     */
    public static function getElasticsearchInstance()
    {
        $adapterName = self::SEARCH_ELASTICA_ADAPTER;

        if (array_key_exists($adapterName, self::$searchInstances) === false) {
            $config = [
                'transport' => ucfirst(Config::get(LibraryConstants::ELASTICA_PARAMETER__TRANSPORT)),
                'port' => Config::get(LibraryConstants::ELASTICA_PARAMETER__PORT),
                'host' => Config::get(LibraryConstants::ELASTICA_PARAMETER__HOST),
            ];

            if (Config::hasValue(LibraryConstants::ELASTICA_PARAMETER__AUTH_HEADER)) {
                $config['headers'] = [
                    'Authorization' => 'Basic ' . Config::get(LibraryConstants::ELASTICA_PARAMETER__AUTH_HEADER),
                ];
            }

            self::$searchInstances[$adapterName] = new Client($config);
        }

        return self::$searchInstances[$adapterName];
    }

    /**
     * @param bool $debug
     *
     * @throws \Exception
     *
     * @return \Spryker\Shared\Library\Storage\Adapter\KeyValue\ReadWriteInterface
     */
    public static function getStorageReadWriteInstance($debug = false)
    {
        return self::getStorageInstance(self::ADAPTER_READ_WRITE, $debug);
    }

    /**
     * @param bool $debug
     *
     * @throws \Exception
     *
     * @return \Spryker\Shared\Library\Storage\Adapter\KeyValue\ReadInterface
     */
    public static function getStorageReadInstance($debug = false)
    {
        return self::getStorageInstance(self::ADAPTER_READ, $debug);
    }

    /**
     * @param string $type
     * @param bool $debug
     *
     * @throws \Exception
     *
     * @return \Spryker\Shared\Library\Storage\AdapterInterface
     */
    protected static function getStorageInstance($type, $debug = false)
    {
        $kvAdapter = Config::get(LibraryConstants::STORAGE_KV_SOURCE);

        $storageAdapter = self::createStorageAdapterName($type, $kvAdapter);
        $configArray = self::createAdapterConfig($kvAdapter);

        $storage = new $storageAdapter($configArray, $debug);
        self::$storageInstances[$storageAdapter] = $storage;

        return self::$storageInstances[$storageAdapter];
    }

    /**
     * @param string $kvAdapter
     *
     * @throws \ErrorException
     * @throws \Exception
     *
     * @return array
     */
    protected static function createAdapterConfig($kvAdapter)
    {
        $config = null;

        switch ($kvAdapter) {
            case static::KV_ADAPTER_REDIS:
                $config = [
                    'protocol' => Config::get(StorageConstants::STORAGE_REDIS_PROTOCOL, Config::get(LibraryConstants::YVES_STORAGE_SESSION_REDIS_PROTOCOL)),
                    'port' => Config::get(StorageConstants::STORAGE_REDIS_PORT, Config::get(LibraryConstants::YVES_STORAGE_SESSION_REDIS_PORT)),
                    'host' => Config::get(StorageConstants::STORAGE_REDIS_HOST, Config::get(LibraryConstants::YVES_STORAGE_SESSION_REDIS_HOST)),
                    'database' => Config::get(StorageConstants::STORAGE_REDIS_DATABASE, static::DEFAULT_DATABASE),
                ];

                // TODO: Remove elseif, only there for BC
                if (Config::hasKey(StorageConstants::STORAGE_REDIS_PASSWORD)) {
                    $config['password'] = Config::get(StorageConstants::STORAGE_REDIS_PASSWORD);
                } elseif (Config::hasKey(LibraryConstants::YVES_STORAGE_SESSION_REDIS_PASSWORD)) {
                    $config['password'] = Config::get(LibraryConstants::YVES_STORAGE_SESSION_REDIS_PASSWORD);
                }

                // TODO: Remove elseif, only there for BC
                $config['persistent'] = false;
                if (Config::hasKey(StorageConstants::STORAGE_PERSISTENT_CONNECTION)) {
                    $config['persistent'] = (bool)Config::get(StorageConstants::STORAGE_PERSISTENT_CONNECTION);
                } elseif (Config::hasKey(LibraryConstants::YVES_STORAGE_SESSION_PERSISTENT_CONNECTION)) {
                    $config['password'] = Config::get(LibraryConstants::YVES_STORAGE_SESSION_PERSISTENT_CONNECTION);
                }
                break;

            case static::SEARCH_ELASTICA_ADAPTER:
                $config = [
                    'transport' => ucfirst(Config::get(LibraryConstants::ELASTICA_PARAMETER__TRANSPORT)),
                    'port' => Config::get(LibraryConstants::ELASTICA_PARAMETER__PORT),
                    'host' => Config::get(LibraryConstants::ELASTICA_PARAMETER__HOST),
                ];

                if (Config::hasValue(LibraryConstants::ELASTICA_PARAMETER__AUTH_HEADER)) {
                    $config['headers'] = [
                        'Authorization' => 'Basic ' . Config::get(LibraryConstants::ELASTICA_PARAMETER__AUTH_HEADER),
                    ];
                }
                break;
        }

        if ($config === null) {
            throw new \ErrorException('Missing implementation for adapter ' . $kvAdapter);
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
        $storageAdapter = self::KV_NAMESPACE . ucfirst(strtolower($kvAdapter)) . $type;

        return $storageAdapter;
    }

}
