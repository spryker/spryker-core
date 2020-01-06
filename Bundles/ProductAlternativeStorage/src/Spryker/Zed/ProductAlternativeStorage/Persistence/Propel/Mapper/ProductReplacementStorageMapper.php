<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductReplacementStorageTransfer;
use Generated\Shared\Transfer\SpyProductReplacementForStorageEntityTransfer;

class ProductReplacementStorageMapper implements ProductReplacementStorageMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyProductReplacementForStorageEntityTransfer $productReplacementStorageEntityTransfer
     * @param \Generated\Shared\Transfer\ProductReplacementStorageTransfer $productReplacementStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductReplacementStorageTransfer
     */
    public function mapProductReplacementStorageEntityToTransfer(
        SpyProductReplacementForStorageEntityTransfer $productReplacementStorageEntityTransfer,
        ProductReplacementStorageTransfer $productReplacementStorageTransfer
    ): ProductReplacementStorageTransfer {
        return $productReplacementStorageTransfer->fromArray($productReplacementStorageEntityTransfer->toArray(), true);
    }
}
