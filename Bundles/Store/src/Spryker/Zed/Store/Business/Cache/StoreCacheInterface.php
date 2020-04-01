<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business\Cache;

use Generated\Shared\Transfer\StoreTransfer;

interface StoreCacheInterface
{
    /**
     * @param int $idStore
     *
     * @return bool
     */
    public function hasStoreByStoreId(int $idStore): bool;

    /**
     * @param string $storeName
     *
     * @return bool
     */
    public function hasStoreByStoreName(string $storeName): bool;

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    public function cacheStore(StoreTransfer $storeTransfer): void;

    /**
     * @param int $idStore
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreByStoreId(int $idStore): StoreTransfer;

    /**
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreByStoreName(string $storeName): StoreTransfer;
}
