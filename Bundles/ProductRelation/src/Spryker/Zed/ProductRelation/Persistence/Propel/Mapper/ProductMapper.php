<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductRelationRelatedProductTransfer;
use Generated\Shared\Transfer\ProductRelationTransfer;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelationProductAbstract;
use Propel\Runtime\Collection\ObjectCollection;

class ProductMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ProductRelation\Persistence\SpyProductRelationProductAbstract[] $productRelationRelatedProductEntities
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer
     */
    public function mapProductRelationRelatedProductEntitiesToProductRelationTransfer(
        ObjectCollection $productRelationRelatedProductEntities,
        ProductRelationTransfer $productRelationTransfer
    ): ProductRelationTransfer {
        foreach ($productRelationRelatedProductEntities as $productRelationRelatedProductEntity) {
            $productRelationTransfer->addRelatedProduct(
                $this->mapProductRelationProductAbstractEntityToProductRelationRelatedProductTransfer(
                    $productRelationRelatedProductEntity,
                    new ProductRelationRelatedProductTransfer()
                )
            );
        }

        return $productRelationTransfer;
    }

    /**
     * @param \Orm\Zed\ProductRelation\Persistence\SpyProductRelationProductAbstract $productRelationProductAbstractEntity
     * @param \Generated\Shared\Transfer\ProductRelationRelatedProductTransfer $productRelationRelatedProductTransfer
     *
     * @return \Generated\Shared\Transfer\ProductRelationRelatedProductTransfer
     */
    public function mapProductRelationProductAbstractEntityToProductRelationRelatedProductTransfer(
        SpyProductRelationProductAbstract $productRelationProductAbstractEntity,
        ProductRelationRelatedProductTransfer $productRelationRelatedProductTransfer
    ): ProductRelationRelatedProductTransfer {
        return $productRelationRelatedProductTransfer->fromArray($productRelationProductAbstractEntity->toArray(), true);
    }
}
