<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Persistence;

use Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer;
use Generated\Shared\Transfer\SpyProductAbstractPackagingStorageEntityTransfer;
use Orm\Zed\ProductPackagingUnitStorage\Persistence\SpyProductAbstractPackagingStorage;

interface ProductPackagingUnitStorageEntityManagerInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer $productAbstractPackagingStorageTransfer
     *
     * @return \Orm\Zed\ProductPackagingUnitStorage\Persistence\SpyProductAbstractPackagingStorage
     */
    public function saveProductAbstractPackagingStorageEntity(ProductAbstractPackagingStorageTransfer $productAbstractPackagingStorageTransfer): SpyProductAbstractPackagingStorage;

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\SpyProductAbstractPackagingStorageEntityTransfer $productAbstractPackagingStorageEntity
     *
     * @return void
     */
    public function deleteProductAbstractPackagingStorageEntity(SpyProductAbstractPackagingStorageEntityTransfer $productAbstractPackagingStorageEntity): void;
}
