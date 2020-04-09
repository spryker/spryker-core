<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Persistence\Mapper;

use Generated\Shared\Transfer\ProductLabelProductAbstractTransfer;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstract;
use Propel\Runtime\Collection\ObjectCollection;

class ProductLabelProductAbstractMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstract[] $productLabelProductAbstractsEntities
     * @param array $productLabelProductAbstractsTransferCollection
     *
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractTransfer[]
     */
    public function mapProductLabelProductAbstractEntitiesToTransferCollection(
        ObjectCollection $productLabelProductAbstractsEntities,
        array $productLabelProductAbstractsTransferCollection
    ): array {
        foreach ($productLabelProductAbstractsEntities as $productLabelProductAbstractsEntity) {
            $productLabelProductAbstractsTransferCollection[] = $this->mapProductLabelProductAbstractEntityToProductLabelProductAbstractTransfer(
                $productLabelProductAbstractsEntity,
                new ProductLabelProductAbstractTransfer()
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
