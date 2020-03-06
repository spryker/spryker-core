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
     * @param int $storeId
     *
     * @return bool
     */
    public function hasStoreTransferByStoreId(int $storeId): bool;

    /**
     * @param string $storeName
     *
     * @return bool
     */
    public function hasStoreTransferByStoreName(string $storeName): bool;

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    public function cacheStoreTransfer(StoreTransfer $storeTransfer): void;

    /**
     * @param int $storeId
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreTransferByStoreId(int $storeId): StoreTransfer;

    /**
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreTransferByStoreName(string $storeName): StoreTransfer;
}
