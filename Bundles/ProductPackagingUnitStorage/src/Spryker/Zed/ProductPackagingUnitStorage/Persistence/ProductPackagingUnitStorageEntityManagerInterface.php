<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Persistence;

use Generated\Shared\Transfer\ProductPackagingUnitStorageTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitStorageEntityTransfer;

interface ProductPackagingUnitStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductPackagingUnitStorageTransfer $productPackagingUnitStorageTransfer
     *
     * @return void
     */
    public function saveProductPackagingUnitStorage(ProductPackagingUnitStorageTransfer $productPackagingUnitStorageTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\SpyProductPackagingUnitStorageEntityTransfer $productPackagingUnitStorageEntity
     *
     * @return void
     */
    public function deleteProductPackagingUnitStorage(SpyProductPackagingUnitStorageEntityTransfer $productPackagingUnitStorageEntity): void;
}
