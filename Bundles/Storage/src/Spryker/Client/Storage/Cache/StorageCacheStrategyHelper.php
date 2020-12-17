<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Storage\Cache;

use Spryker\Client\Storage\StorageClient;
use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Client\Storage\StorageConfig;

class StorageCacheStrategyHelper implements StorageCacheStrategyHelperInterface
{
    /**
     * @var \Spryker\Client\Storage\StorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\Storage\StorageConfig
     */
    protected $storageConfig;

    /**
     * @param \Spryker\Client\Storage\StorageClientInterface $storageClient
     * @param \Spryker\Client\Storage\StorageConfig $storageConfig
     */
    public function __construct(
        StorageClientInterface $storageClient,
        StorageConfig $storageConfig
    ) {
        $this->storageClient = $storageClient;
        $this->storageConfig = $storageConfig;
    }

    /**
     * @param string $cacheKey
     *
     * @return void
     */
    public function setCache($cacheKey)
    {
        $ttl = $this->storageConfig->getStorageCacheTtl();
        $this->storageClient->getService()->set($cacheKey, json_encode(array_keys($this->getCachedKeys())), $ttl);
    }

    /**
     * @return array
     */
    public function getCachedKeys()
    {
        return $this->storageClient->getCachedKeys();
    }

    /**
     * @param string $key
     *
     * @return void
     */
    public function unsetCachedKey($key)
    {
        $this->storageClient->unsetCachedKey($key);
    }

    /**
     * @return void
     */
    public function unsetLastCachedKey()
    {
        $this->storageClient->unsetLastCachedKey();
    }

    /**
     * @param string $status
     *
     * @return bool
     */
    public function isNewKey($status)
    {
        return $status === StorageClient::KEY_NEW;
    }

    /**
     * @param string $status
     *
     * @return bool
     */
    public function isUsedKey($status)
    {
        return $status === StorageClient::KEY_USED;
    }

    /**
     * @param string $status
     *
     * @return bool
     */
    public function isUnusedKey($status)
    {
        return $status === StorageClient::KEY_INIT;
    }
}
