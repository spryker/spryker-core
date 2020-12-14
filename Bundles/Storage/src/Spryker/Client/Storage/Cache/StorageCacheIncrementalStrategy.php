<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Storage\Cache;

use Spryker\Client\Storage\StorageClient;
use Spryker\Client\Storage\StorageConfig;

class StorageCacheIncrementalStrategy implements StorageCacheStrategyInterface
{
    /**
     * @var \Spryker\Client\Storage\Cache\StorageCacheStrategyHelperInterface
     */
    protected $storageCacheStrategyHelper;

    /**
     * @var \Spryker\Client\Storage\StorageConfig
     */
    protected $storageClientConfig;

    /**
     * @param \Spryker\Client\Storage\Cache\StorageCacheStrategyHelperInterface $storageCacheStrategyHelper
     * @param \Spryker\Client\Storage\StorageConfig $storageClientConfig
     */
    public function __construct(
        StorageCacheStrategyHelperInterface $storageCacheStrategyHelper,
        StorageConfig $storageClientConfig
    ) {
        $this->storageCacheStrategyHelper = $storageCacheStrategyHelper;
        $this->storageClientConfig = $storageClientConfig;
    }

    /**
     * @param string $cacheKey
     *
     * @return void
     */
    public function updateCache($cacheKey)
    {
        $areKeysRemoved = $this->handleOverLimit();
        $isUpdateCacheNeeded = $this->isUpdateCacheNeeded($areKeysRemoved);

        if ($isUpdateCacheNeeded) {
            $this->storageCacheStrategyHelper->setCache($cacheKey);
        }
    }

    /**
     * @return bool
     */
    protected function handleOverLimit()
    {
        $areUnusedKeysRemoved = false;

        if ($this->isOverLimit()) {
            $areUnusedKeysRemoved = $this->removeOverLimitUnusedKeys();

            $stillOverLimit = $this->isOverLimit();
            if ($stillOverLimit) {
                $this->removeOverLimitKeysFromEnd();
            }
        }

        return $areUnusedKeysRemoved;
    }

    /**
     * @return int
     */
    protected function getOverLimitSize()
    {
        return count($this->storageCacheStrategyHelper->getCachedKeys()) - $this->storageClientConfig->getStorageCacheIncrementalStrategyKeySizeLimit();
    }

    /**
     * @return bool
     */
    protected function isOverLimit()
    {
        return $this->getOverLimitSize() > 0;
    }

    /**
     * @return bool
     */
    protected function removeOverLimitUnusedKeys()
    {
        $areUnusedKeysRemoved = false;

        foreach ($this->storageCacheStrategyHelper->getCachedKeys() as $key => $status) {
            if ($this->storageCacheStrategyHelper->isUnusedKey($status) && $this->isOverLimit()) {
                $this->storageCacheStrategyHelper->unsetCachedKey($key);
                $areUnusedKeysRemoved = true;
            }
        }

        return $areUnusedKeysRemoved;
    }

    /**
     * @return void
     */
    protected function removeOverLimitKeysFromEnd()
    {
        $overLimitSize = $this->getOverLimitSize();

        for ($i = 0; $i < $overLimitSize; $i++) {
            $this->storageCacheStrategyHelper->unsetLastCachedKey();
        }
    }

    /**
     * @param bool $areKeysRemoved
     *
     * @return bool
     */
    protected function isUpdateCacheNeeded($areKeysRemoved)
    {
        if ($this->isNewKeyAdded() || $areKeysRemoved) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function isNewKeyAdded()
    {
        return array_search(StorageClient::KEY_NEW, $this->storageCacheStrategyHelper->getCachedKeys()) !== false;
    }
}
