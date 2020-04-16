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
     * @param int $idStore
     *
     * @return bool
     */
    public function hasStoreByStoreId(int $idStore): bool
    {
        return isset(static::$storeTransfersCacheByStoreId[$idStore]);
    }

    /**
     * @param string $storeName
     *
     * @return bool
     */
    public function hasStoreByStoreName(string $storeName): bool
    {
        return isset(static::$storeTransferCacheByStoreName[$storeName]);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    public function cacheStore(StoreTransfer $storeTransfer): void
    {
        static::$storeTransferCacheByStoreName[$storeTransfer->getName()] = $storeTransfer;
        static::$storeTransfersCacheByStoreId[$storeTransfer->getIdStore()] = $storeTransfer;
    }

    /**
     * @param int $idStore
     *
     * @throws \Spryker\Zed\Store\Business\Exception\StoreCacheNotFoundException
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreByStoreId(int $idStore): StoreTransfer
    {
        if (!$this->hasStoreByStoreId($idStore)) {
            throw new StoreCacheNotFoundException();
        }

        return static::$storeTransfersCacheByStoreId[$idStore];
    }

    /**
     * @param string $storeName
     *
     * @throws \Spryker\Zed\Store\Business\Exception\StoreCacheNotFoundException
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreByStoreName(string $storeName): StoreTransfer
    {
        if (!$this->hasStoreByStoreName($storeName)) {
            throw new StoreCacheNotFoundException();
        }

        return static::$storeTransferCacheByStoreName[$storeName];
    }
}
