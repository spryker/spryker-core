<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Storage;

use Generated\Shared\Transfer\StorageScanResultTransfer;
use Spryker\Client\Kernel\AbstractClient;
use Spryker\Client\Storage\Exception\InvalidStorageScanPluginInterfaceException;
use Spryker\Client\Storage\Redis\Service;
use Spryker\Client\StorageExtension\Dependency\Plugin\StorageScanPluginInterface;
use Spryker\Shared\Storage\StorageConstants;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Client\Storage\StorageFactory getFactory()
 */
class StorageClient extends AbstractClient implements StorageClientInterface
{
    public const KEY_NAME_PREFIX = 'storage';
    public const KEY_NAME_SEPARATOR = ':';
    public const KEY_USED = 'used';
    public const KEY_NEW = 'new';
    public const KEY_INIT = 'init';

    protected const CACHE_KEY_PREFIX = 'cache';

    /**
     * All keys which have been used for the last request with same URL
     *
     * @var array|null
     */
    public static $cachedKeys;

    /**
     * Pre-loaded values for this URL from Storage
     *
     * @var array|null
     */
    protected static $bufferedValues;

    /**
     * The Buffer for already decoded buffered values
     *
     * @var array|null
     */
    protected static $bufferedDecodedValues;

    /**
     * @var \Spryker\Client\Storage\Redis\ServiceInterface|\Spryker\Client\StorageExtension\Dependency\Plugin\StoragePluginInterface|\Spryker\Client\StorageExtension\Dependency\Plugin\StorageScanPluginInterface|null
     */
    public static $service;

    /**
     * @api
     *
     * @return \Spryker\Client\Storage\Redis\ServiceInterface|\Spryker\Client\StorageExtension\Dependency\Plugin\StoragePluginInterface|\Spryker\Client\StorageExtension\Dependency\Plugin\StorageScanPluginInterface
     */
    public function getService()
    {
        if (self::$service === null) {
            self::$service = $this->getFactory()->createCachedService();
        }

        return self::$service;
    }

    /**
     * @api
     *
     * @return array|null
     */
    public function getCachedKeys()
    {
        return static::$cachedKeys;
    }

    /**
     * @api
     *
     * @param array|null $keys
     *
     * @return array|null
     */
    public function setCachedKeys($keys)
    {
        return static::$cachedKeys = $keys;
    }

    /**
     * @api
     *
     * @return void
     */
    public function resetCache()
    {
        self::$cachedKeys = null;
        self::$bufferedValues = null;
        self::$bufferedDecodedValues = null;
    }

    /**
     * @api
     *
     * @param string $key
     * @param mixed $value
     * @param int|null $ttl
     *
     * @return void
     */
    public function set($key, $value, $ttl = null)
    {
        $this->getService()->set($key, $value, $ttl);
    }

    /**
     * @api
     *
     * @param array $items
     *
     * @return void
     */
    public function setMulti(array $items)
    {
        $this->getService()->setMulti($items);
    }

    /**
     * @api
     *
     * @param string $key
     *
     * @return void
     */
    public function unsetCachedKey($key)
    {
        unset(static::$cachedKeys[$key]);
    }

    /**
     * @api
     *
     * @return void
     */
    public function unsetLastCachedKey()
    {
        array_pop(static::$cachedKeys);
    }

    /**
     * @api
     *
     * @param string $key
     *
     * @return void
     */
    public function delete($key)
    {
        $this->getService()->delete($key);
    }

    /**
     * @api
     *
     * @param array $keys
     *
     * @return void
     */
    public function deleteMulti(array $keys)
    {
        $this->getService()->deleteMulti($keys);
    }

    /**
     * @api
     *
     * @return int
     */
    public function deleteAll()
    {
        return $this->getService()->deleteAll();
    }

    /**
     * @api
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        $this->loadCacheKeysAndValues();

        if (!array_key_exists($key, self::$bufferedValues)) {
            self::$cachedKeys[$key] = self::KEY_NEW;

            return $this->getService()->get($key);
        }

        self::$cachedKeys[$key] = self::KEY_USED;

        if (!array_key_exists($key, self::$bufferedDecodedValues)) {
            self::$bufferedDecodedValues[$key] = $this->jsonDecode(self::$bufferedValues[$key]);
        }

        return self::$bufferedDecodedValues[$key];
    }

    /**
     * @api
     *
     * @param array $keys
     *
     * @return array
     */
    public function getMulti(array $keys)
    {
        $this->loadCacheKeysAndValues();

        // Get immediately available values
        $keyValues = array_intersect_key(self::$bufferedValues, array_flip($keys));

        foreach ($keyValues as $key => $keyValue) {
            self::$cachedKeys[$key] = self::KEY_USED;
        }

        $allPreparedKeys = $this->prefixKeyValues(array_flip($keys));

        // Get the rest of requested keys without a value
        $keys = array_diff($keys, array_keys($keyValues));

        $keyValues = $this->prefixKeyValues($keyValues);

        if ($keys) {
            $keyValues += $this->getService()->getMulti($keys);
            self::$cachedKeys += array_fill_keys($keys, self::KEY_NEW);
        }

        return array_merge($allPreparedKeys, $keyValues);
    }

    /**
     * @api
     *
     * @return array
     */
    public function getStats()
    {
        return $this->getService()->getStats();
    }

    /**
     * @api
     *
     * @return array
     */
    public function getAllKeys()
    {
        return $this->getService()->getAllKeys();
    }

    /**
     * @api
     *
     * @return void
     */
    public function resetAccessStats()
    {
        $this->getService()->resetAccessStats();
    }

    /**
     * @api
     *
     * @return array
     */
    public function getAccessStats()
    {
        return $this->getService()->getAccessStats();
    }

    /**
     * @api
     *
     * @return int
     */
    public function getCountItems()
    {
        return $this->getService()->getCountItems();
    }

    /**
     * @return void
     */
    protected function loadCacheKeysAndValues()
    {
        if (self::$cachedKeys === null) {
            $this->loadKeysFromCache();
        }

        if (self::$bufferedValues === null) {
            $this->loadAllValues();
        }
    }

    /**
     * @return void
     */
    protected function loadKeysFromCache()
    {
        self::$cachedKeys = [];
        $cacheKey = $this->buildCacheKey();

        if (!$cacheKey) {
            return;
        }

        $cachedKeys = $this->getService()->get($cacheKey);

        if ($cachedKeys && is_array($cachedKeys)) {
            foreach ($cachedKeys as $key) {
                self::$cachedKeys[$key] = self::KEY_INIT;
            }
        }
    }

    /**
     * @param array $keyValues
     *
     * @return array
     */
    protected function prefixKeyValues(array $keyValues)
    {
        $prefixedKeyValues = [];

        foreach ($keyValues as $key => $value) {
            $prefixedKeyValues[$this->getKeyPrefix() . $key] = $value;
        }

        return $prefixedKeyValues;
    }

    /**
     * Pre-Loads all values from storage with mget()
     *
     * @return void
     */
    protected function loadAllValues()
    {
        self::$bufferedValues = [];
        self::$bufferedDecodedValues = [];

        if (!empty(self::$cachedKeys) && is_array(self::$cachedKeys)) {
            $values = $this->getService()->getMulti(array_keys(self::$cachedKeys));

            if (!empty($values) && is_array($values)) {
                foreach ($values as $key => $value) {
                    $keySuffix = substr($key, strlen($this->getKeyPrefix()));
                    self::$bufferedValues[$keySuffix] = $value;
                }
            }
        }
    }

    /**
     * @return string
     */
    protected function getKeyPrefix()
    {
        return Service::KV_PREFIX;
    }

    /**
     * @api
     *
     * @param string $pattern
     *
     * @return array
     */
    public function getKeys($pattern = '*')
    {
        return $this->getService()->getKeys($pattern);
    }

    /**
     * @api
     *
     * @param string $pattern
     * @param int $limit
     * @param int|null $cursor
     *
     * @throws \Spryker\Client\Storage\Exception\InvalidStorageScanPluginInterfaceException
     *
     * @return \Generated\Shared\Transfer\StorageScanResultTransfer
     */
    public function scanKeys(string $pattern, int $limit, ?int $cursor = 0): StorageScanResultTransfer
    {
        if (!$this->getService() instanceof StorageScanPluginInterface) {
            throw new InvalidStorageScanPluginInterfaceException(
                'In order to use the method `scanKeys` you need a service that implements the plugin interface `StorageScanPluginInterface`'
            );
        }

        return $this->getService()->scanKeys($pattern, $limit, $cursor);
    }

    /**
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $storageCacheStrategyName
     *
     * @return void
     */
    public function persistCacheForRequest(Request $request, $storageCacheStrategyName = StorageConstants::STORAGE_CACHE_STRATEGY_REPLACE)
    {
        $cacheKey = $this->buildCacheKey($request);

        if ($cacheKey && is_array(self::$cachedKeys)) {
            $this->updateCache($storageCacheStrategyName, $cacheKey);
        }
    }

    /**
     * @api
     *
     * @deprecated Use persistCacheForRequest() instead.
     *
     * @param \Symfony\Component\HttpFoundation\Request|null $request
     *
     * @return void
     */
    public static function persistCache(?Request $request = null)
    {
        $cacheKey = static::generateCacheKey($request);

        if ($cacheKey && is_array(self::$cachedKeys)) {
            $updateCache = false;
            foreach (self::$cachedKeys as $key => $status) {
                if ($status === self::KEY_INIT) {
                    unset(self::$cachedKeys[$key]);
                }

                if ($status !== self::KEY_USED) {
                    $updateCache = true;
                }
            }

            if ($updateCache) {
                $ttl = self::getFactory()
                    ->getStorageClientConfig()
                    ->getStorageCacheTtl();

                self::$service->set($cacheKey, json_encode(array_keys(self::$cachedKeys)), $ttl);
            }
        }
    }

    /**
     * This method exists for BC reasons only and should be removed with next major release.
     *
     * @deprecated Use `Spryker\Client\Storage\StorageClient::buildCacheKey()` instead.
     *
     * @param \Symfony\Component\HttpFoundation\Request|null $request
     *
     * @return string
     */
    protected static function generateCacheKey(?Request $request = null): string
    {
        return (new static())->getFactory()->createCacheKeyGenerator()->generateCacheKey($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request|null $request
     *
     * @return string
     */
    protected function buildCacheKey(?Request $request = null): string
    {
        return $this->getFactory()->createCacheKeyGenerator()->generateCacheKey($request);
    }

    /**
     * @param string $storageCacheStrategyName
     * @param string $cacheKey
     *
     * @return void
     */
    protected function updateCache($storageCacheStrategyName, $cacheKey): void
    {
        $this->getFactory()
            ->createStorageCacheStrategy($storageCacheStrategyName)
            ->updateCache($cacheKey);
    }

    /**
     * @param string $value
     *
     * @return mixed
     */
    protected function jsonDecode($value)
    {
        $result = json_decode($value, true);

        if (json_last_error() === JSON_ERROR_SYNTAX) {
            return $value;
        }

        return $result;
    }
}
