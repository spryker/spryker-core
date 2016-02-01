<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Library\Storage;

use Elastica\Client;
use Spryker\Shared\Config;
use Spryker\Shared\Library\LibraryConstants;

/**
 * Class StorageInstanceBuilder
 */
class StorageInstanceBuilder
{

    const KV_NAMESPACE = '\Spryker\Shared\Library\Storage\Adapter\KeyValue\\';
    const SEARCH_ELASTICA_ADAPTER = 'elastica';
    const ADAPTER_READ_WRITE = 'ReadWrite';
    const ADAPTER_READ = 'Read';
    const ADAPTER_LOCAL = 'Local';
    const KV_ADAPTER_REDIS = 'redis';

    /**
     * @var AdapterInterface[]
     */
    private static $storageInstances = [];

    /**
     * @var array
     */
    private static $searchInstances = [];

    /**
     * @throws \ErrorException
     *
     * @return \Elastica\Client
     */
    public static function getElasticsearchInstance()
    {
        $adapterName = self::SEARCH_ELASTICA_ADAPTER;

        if (array_key_exists($adapterName, self::$searchInstances) === false) {
            self::$searchInstances[$adapterName] = new Client([
                'protocol' => Config::get(LibraryConstants::ELASTICA_PARAMETER__TRANSPORT),
                'port' => Config::get(LibraryConstants::ELASTICA_PARAMETER__PORT),
                'host' => Config::get(LibraryConstants::ELASTICA_PARAMETER__HOST),
            ]);
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
     * @return AdapterInterface
     */
    private static function getStorageInstance($type, $debug = false)
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
        switch ($kvAdapter) {
            case self::KV_ADAPTER_REDIS:
                return [
                    'protocol' => Config::get(LibraryConstants::YVES_STORAGE_SESSION_REDIS_PROTOCOL),
                    'port' => Config::get(LibraryConstants::YVES_STORAGE_SESSION_REDIS_PORT),
                    'host' => Config::get(LibraryConstants::YVES_STORAGE_SESSION_REDIS_HOST),
                ];
            case self::SEARCH_ELASTICA_ADAPTER:
                return [
                    'protocol' => Config::get(LibraryConstants::ELASTICA_PARAMETER__TRANSPORT),
                    'port' => Config::get(LibraryConstants::ELASTICA_PARAMETER__PORT),
                    'host' => Config::get(LibraryConstants::ELASTICA_PARAMETER__HOST),
                ];
        }
        throw new \ErrorException('Missing implementation for adapter ' . $kvAdapter);
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
