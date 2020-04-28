<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Persistence\Mapper;

use Generated\Shared\Transfer\ProductLabelProductAbstractTransfer;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstract;
use Propel\Runtime\Collection\ObjectCollection;

class ProductLabelProductAbstractsMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstract[] $productLabelProductAbstractsEntities
     * @param \Generated\Shared\Transfer\ProductLabelProductAbstractTransfer[] $productLabelProductAbstractsTransfers
     *
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractTransfer[]
     */
    public function mapProductLabelProductAbstractEntitiesToProductLabelProductAbstractTransfers(
        ObjectCollection $productLabelProductAbstractsEntities,
        array $productLabelProductAbstractsTransfers
    ): array {
        foreach ($productLabelProductAbstractsEntities as $productLabelProductAbstractsEntity) {
            $productLabelProductAbstractsTransfers[] = $this->mapProductLabelProductAbstractEntityToProductLabelProductAbstractTransfer(
                $productLabelProductAbstractsEntity,
                new ProductLabelProductAbstractTransfer()
            );
        }

        return $productLabelProductAbstractsTransfers;
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
