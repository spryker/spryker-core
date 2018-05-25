<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Persistence\Mapper;

use Generated\Shared\Transfer\ProductAlternativeTransfer;
use Generated\Shared\Transfer\SpyProductAlternativeEntityTransfer;
use Orm\Zed\ProductAlternative\Persistence\SpyProductAlternative;

class ProductAlternativeMapper implements ProductAlternativeMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeTransfer $productAlternativeTransfer
     *
     * @return \Generated\Shared\Transfer\SpyProductAlternativeEntityTransfer
     */
    public function mapProductAlternativeTransferToEntityTransfer(
        ProductAlternativeTransfer $productAlternativeTransfer
    ): SpyProductAlternativeEntityTransfer {
        return (new SpyProductAlternativeEntityTransfer())
            ->fromArray($productAlternativeTransfer->toArray(), true);
    }

    /**
     * @param \Orm\Zed\ProductAlternative\Persistence\SpyProductAlternative $spyProductAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function mapSpyProductAlternativeEntityToTransfer(
        SpyProductAlternative $spyProductAlternative
    ): ProductAlternativeTransfer {
        return (new ProductAlternativeTransfer())
            ->fromArray($spyProductAlternative->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductAlternativeEntityTransfer $spyProductAlternativeEntityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function mapSpyProductAlternativeEntityTransferToTransfer(
        SpyProductAlternativeEntityTransfer $spyProductAlternativeEntityTransfer
    ): ProductAlternativeTransfer {
        return (new ProductAlternativeTransfer())
            ->fromArray($spyProductAlternativeEntityTransfer->toArray(), true);
    }
}
