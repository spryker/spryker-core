<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Persistence\Mapper;

use Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer;

interface ProductPackagingUnitStorageMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer $productPackagingUnitEntityTransfer
     * @param \Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer $productConcretePackagingStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer
     */
    public function mapProductConcretePackagingStorageEntityTransferToStorageTransfer(
        SpyProductPackagingUnitEntityTransfer $productPackagingUnitEntityTransfer,
        ProductConcretePackagingStorageTransfer $productConcretePackagingStorageTransfer
    ): ProductConcretePackagingStorageTransfer;
}
