<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Storage\Cache;

class StorageCacheReplaceStrategy implements StorageCacheStrategyInterface
{
    /**
     * @var \Spryker\Client\Storage\Cache\StorageCacheStrategyHelperInterface
     */
    protected $storageCacheStrategyHelper;

    /**
     * @param \Spryker\Client\Storage\Cache\StorageCacheStrategyHelperInterface $storageCacheStrategyHelper
     */
    public function __construct(StorageCacheStrategyHelperInterface $storageCacheStrategyHelper)
    {
        $this->storageCacheStrategyHelper = $storageCacheStrategyHelper;
    }

    /**
     * @param string $cacheKey
     *
     * @return void
     */
    public function updateCache($cacheKey)
    {
        $isUpdateCacheNeeded = false;

        foreach ($this->storageCacheStrategyHelper->getCachedKeys() as $key => $status) {
            if ($this->storageCacheStrategyHelper->isUnusedKey($status)) {
                $this->storageCacheStrategyHelper->unsetCachedKey($key);
            }

            if ($this->isUpdateNeeded($status)) {
                $isUpdateCacheNeeded = true;
            }
        }

        if ($isUpdateCacheNeeded) {
            $this->storageCacheStrategyHelper->setCache($cacheKey);
        }
    }

    /**
     * @param string $status
     *
     * @return bool
     */
    protected function isUpdateNeeded($status)
    {
        return $this->storageCacheStrategyHelper->isNewKey($status) || $this->storageCacheStrategyHelper->isUnusedKey($status);
    }
}
