<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductAlternativeStorageTransfer;
use Generated\Shared\Transfer\SpyProductAlternativeStorageEntityTransfer;

class ProductAlternativeStorageMapper implements ProductAlternativeStorageMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyProductAlternativeStorageEntityTransfer $productAlternativeStorageEntityTransfer
     * @param \Generated\Shared\Transfer\ProductAlternativeStorageTransfer $productAlternativeStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeStorageTransfer
     */
    public function mapProductAlternativeStorageEntityToTransfer(
        SpyProductAlternativeStorageEntityTransfer $productAlternativeStorageEntityTransfer,
        ProductAlternativeStorageTransfer $productAlternativeStorageTransfer
    ): ProductAlternativeStorageTransfer {
        return $productAlternativeStorageTransfer->fromArray($productAlternativeStorageEntityTransfer->toArray(), true);
    }
}
