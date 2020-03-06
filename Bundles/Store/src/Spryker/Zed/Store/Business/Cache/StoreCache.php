<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business\Cache;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Store\Business\Exception\StoreCacheNotFoundException;

class StoreCache implements StoreCacheInterface
{
    /**
     * @var \Generated\Shared\Transfer\StoreTransfer[]
     */
    protected static $storeTransfersCacheByStoreId = [];

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer[]
     */
    protected static $storeTransferCacheByStoreName = [];

    /**
     * @param int $storeId
     *
     * @return bool
     */
    public function hasStoreTransferByStoreId(int $storeId): bool
    {
        return isset(static::$storeTransfersCacheByStoreId[$storeId]);
    }

    /**
     * @param string $storeName
     *
     * @return bool
     */
    public function hasStoreTransferByStoreName(string $storeName): bool
    {
        return isset(static::$storeTransferCacheByStoreName[$storeName]);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    public function cacheStoreTransfer(StoreTransfer $storeTransfer): void
    {
        static::$storeTransferCacheByStoreName[$storeTransfer->getName()] = $storeTransfer;
        static::$storeTransfersCacheByStoreId[$storeTransfer->getIdStore()] = $storeTransfer;
    }

    /**
     * @param int $storeId
     *
     * @throws \Spryker\Zed\Store\Business\Exception\StoreCacheNotFoundException
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreTransferByStoreId(int $storeId): StoreTransfer
    {
        if (!$this->hasStoreTransferByStoreId($storeId)) {
            throw new StoreCacheNotFoundException();
        }

        return static::$storeTransfersCacheByStoreId[$storeId];
    }

    /**
     * @param string $storeName
     *
     * @throws \Spryker\Zed\Store\Business\Exception\StoreCacheNotFoundException
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreTransferByStoreName(string $storeName): StoreTransfer
    {
        if (!$this->hasStoreTransferByStoreName($storeName)) {
            throw new StoreCacheNotFoundException();
        }

        return static::$storeTransferCacheByStoreName[$storeName];
    }
}
