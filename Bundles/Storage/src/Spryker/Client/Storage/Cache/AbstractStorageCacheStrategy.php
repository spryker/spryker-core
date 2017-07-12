<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Storage\Cache;

use Spryker\Client\Storage\StorageClient;
use Spryker\Client\Storage\StorageClientWithCacheInterface;
use Spryker\Zed\Storage\StorageConfig;

// @todo remove abstract
abstract class AbstractStorageCacheStrategy implements StorageCacheStrategyInterface
{

    /**
     * @var \Spryker\Client\Storage\StorageClientWithCacheInterface
     */
    protected $storageClient;

    /**
     * @param \Spryker\Client\Storage\StorageClientWithCacheInterface $storageClient
     */
    public function __construct(StorageClientWithCacheInterface $storageClient)
    {
        $this->storageClient = $storageClient;
    }

    /**
     * @param string $cacheKey
     *
     * @return void
     */
    protected function setCache($cacheKey)
    {
        $ttl = StorageConfig::STORAGE_CACHE_TTL;
        $this->storageClient->getService()->set($cacheKey, json_encode(array_keys($this->getCachedKeys())), $ttl);
    }

    /**
     * @return array
     */
    protected function getCachedKeys()
    {
        return $this->storageClient->getCachedKeys();
    }

    /**
     * @param string $key
     *
     * @return void
     */
    protected function unsetCachedKey($key)
    {
        $this->storageClient->unsetCachedKey($key);
    }

    /**
     * @return void
     */
    protected function unsetLastCachedKey()
    {
        $this->storageClient->unsetLastCachedKey();
    }

    /**
     * @param string $status
     *
     * @return bool
     */
    protected function isNewKey($status)
    {
        return $status === StorageClient::KEY_NEW;
    }

    /**
     * @param string $status
     *
     * @return bool
     */
    protected function isUsedKey($status)
    {
        return $status === StorageClient::KEY_USED;
    }

    /**
     * @param string $status
     *
     * @return bool
     */
    protected function isUnusedKey($status)
    {
        return $status === StorageClient::KEY_INIT;
    }

}
