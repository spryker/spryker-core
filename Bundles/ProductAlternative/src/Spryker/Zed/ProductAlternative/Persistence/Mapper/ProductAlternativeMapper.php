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
     * @param \Generated\Shared\Transfer\SpyProductAlternativeEntityTransfer $productAlternativeEntityTransfer
     * @param \Orm\Zed\ProductAlternative\Persistence\SpyProductAlternative $product
     *
     * @return \Orm\Zed\ProductAlternative\Persistence\SpyProductAlternative
     */
    public function mapSpyProductAlternativeEntityTransferToEntity(
        SpyProductAlternativeEntityTransfer $productAlternativeEntityTransfer,
        SpyProductAlternative $product
    ): SpyProductAlternative {
        $product->fromArray(
            $productAlternativeEntityTransfer->toArray()
        );

        return $product;
    }

    /**
     * @param \Orm\Zed\ProductAlternative\Persistence\SpyProductAlternative $productAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function mapSpyProductAlternativeEntityToTransfer(
        SpyProductAlternative $productAlternative
    ): ProductAlternativeTransfer {
        $productAlternativeTransfer = (new ProductAlternativeTransfer())
            ->fromArray($productAlternative->toArray(), true);

        $productAlternativeTransfer
            ->setIdProduct($productAlternative->getFkProduct())
            ->setIdProductAbstractAlternative($productAlternative->getFkProductAbstractAlternative())
            ->setIdProductConcreteAlternative($productAlternative->getFkProductConcreteAlternative());

        return $productAlternativeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeTransfer $productAlternativeTransfer
     *
     * @return \Generated\Shared\Transfer\SpyProductAlternativeEntityTransfer
     */
    public function mapProductAlternativeTransferToEntityTransfer(
        ProductAlternativeTransfer $productAlternativeTransfer
    ): SpyProductAlternativeEntityTransfer {
        $productAlternativeEntityTransfer = (new SpyProductAlternativeEntityTransfer())
            ->fromArray($productAlternativeTransfer->toArray(), true);

        $productAlternativeEntityTransfer
            ->setFkProduct($productAlternativeTransfer->getIdProduct())
            ->setFkProductAbstractAlternative($productAlternativeTransfer->getIdProductAbstractAlternative())
            ->setFkProductConcreteAlternative($productAlternativeTransfer->getIdProductConcreteAlternative());

        return (new SpyProductAlternativeEntityTransfer())
            ->fromArray($productAlternativeTransfer->toArray(), true);
    }
}
