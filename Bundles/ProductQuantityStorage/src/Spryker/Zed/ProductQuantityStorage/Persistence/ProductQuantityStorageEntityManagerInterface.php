<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantityStorage\Persistence;

use Generated\Shared\Transfer\SpyProductQuantityStorageEntityTransfer;

interface ProductQuantityStorageEntityManagerInterface
{
    /**
     * @param int $idProductQuantityStorage
     * @param bool $isSendingToQueue
     *
     * @return void
     */
    public function deleteProductQuantityStorage(int $idProductQuantityStorage, bool $isSendingToQueue = true): void;

    /**
     * @param \Generated\Shared\Transfer\SpyProductQuantityStorageEntityTransfer $productQuantityStorageEntity
     * @param bool $isSendingToQueue
     *
     * @return void
     */
    public function saveProductQuantityStorageEntity(
        SpyProductQuantityStorageEntityTransfer $productQuantityStorageEntity,
        bool $isSendingToQueue = true
    ): void;
}
