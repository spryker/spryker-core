<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Persistence\Mapper;

use Generated\Shared\Transfer\ProductAlternativeTransfer;
use Generated\Shared\Transfer\SpyProductAlternativeEntityTransfer;

class ProductAlternativeMapper implements ProductAlternativeMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyProductAlternativeEntityTransfer $productAlternativeEntityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function mapProductAlternativeEntityTransferToEntity(
        SpyProductAlternativeEntityTransfer $productAlternativeEntityTransfer
    ): ProductAlternativeTransfer {
        return (new ProductAlternativeTransfer())
            ->fromArray($productAlternativeEntityTransfer->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeTransfer $productAlternativeTransfer
     *
     * @return \Generated\Shared\Transfer\SpyProductAlternativeEntityTransfer
     */
    public function mapProductAlternativeEntityToEntityTransfer(
        ProductAlternativeTransfer $productAlternativeTransfer
    ): SpyProductAlternativeEntityTransfer {
        return (new SpyProductAlternativeEntityTransfer())
            ->fromArray($productAlternativeTransfer->toArray(), true);
    }
}
