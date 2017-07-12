<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Storage\Cache;

use Spryker\Zed\Storage\StorageConfig;

class StorageCacheIncrementalStrategy extends AbstractStorageCacheStrategy
{

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
            $this->setCache($cacheKey);
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
        return count($this->getCachedKeys()) - StorageConfig::STORAGE_CACHE_STRATEGY_INCREMENTAL_KEY_SIZE_LIMIT;
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

        foreach ($this->getCachedKeys() as $key => $status) {
            if ($this->isUnusedKey($status) && $this->isOverLimit()) {
                $this->unsetCachedKey($key);
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
            $this->unsetLastCachedKey();
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
     *
     * @todo array_search or not
     */
    protected function isNewKeyAdded()
    {
        foreach ($this->getCachedKeys() as $key => $status) {
            if ($this->isNewKey($status)) {
                return true;
            }
        }

        return false;
    }

}
