<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Persistence;

use Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer;
use Generated\Shared\Transfer\SpyProductConcretePackagingStorageEntityTransfer;

interface ProductPackagingUnitStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer $productConcretePackagingStorageTransfer
     *
     * @return void
     */
    public function saveProductConcretePackagingStorageEntity(ProductConcretePackagingStorageTransfer $productConcretePackagingStorageTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\SpyProductConcretePackagingStorageEntityTransfer $productConcretePackagingStorageEntity
     *
     * @return void
     */
    public function deleteProductConcretePackagingStorageEntity(SpyProductConcretePackagingStorageEntityTransfer $productConcretePackagingStorageEntity): void;
}
