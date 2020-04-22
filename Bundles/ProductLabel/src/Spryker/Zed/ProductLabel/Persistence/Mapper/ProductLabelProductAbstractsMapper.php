<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Persistence\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ProductLabelProductAbstractTransfer;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstract;
use Propel\Runtime\Collection\ObjectCollection;

class ProductLabelProductAbstractsMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstract[] $productLabelProductAbstractsEntities
     * @param \ArrayObject $productLabelProductAbstractsTransferCollection
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductLabelProductAbstractTransfer[]
     */
    public function mapProductLabelProductAbstractEntitiesToProductLabelProductAbstractTransferCollection(
        ObjectCollection $productLabelProductAbstractsEntities,
        ArrayObject $productLabelProductAbstractsTransferCollection
    ): ArrayObject {
        foreach ($productLabelProductAbstractsEntities as $productLabelProductAbstractsEntity) {
            $productLabelProductAbstractsTransferCollection->append(
                $this->mapProductLabelProductAbstractEntityToProductLabelProductAbstractTransfer(
                    $productLabelProductAbstractsEntity,
                    new ProductLabelProductAbstractTransfer()
                )
            );
        }

        return $productLabelProductAbstractsTransferCollection;
    }

    /**
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstract $productLabelProductAbstractEntity
     * @param \Generated\Shared\Transfer\ProductLabelProductAbstractTransfer $productLabelProductAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractTransfer
     */
    protected function mapProductLabelProductAbstractEntityToProductLabelProductAbstractTransfer(
        SpyProductLabelProductAbstract $productLabelProductAbstractEntity,
        ProductLabelProductAbstractTransfer $productLabelProductAbstractTransfer
    ): ProductLabelProductAbstractTransfer {
        return $productLabelProductAbstractTransfer->fromArray($productLabelProductAbstractEntity->toArray(), true);
    }
}
