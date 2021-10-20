<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Persistence\Mapper;

use Generated\Shared\Transfer\ProductLabelProductAbstractTransfer;
use Orm\Zed\ProductLabel\Persistence\Base\SpyProductLabelProductAbstract;
use Propel\Runtime\Collection\ObjectCollection;

class ProductLabelProductAbstractMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstract> $productLabelProductAbstractEntities
     * @param array<\Generated\Shared\Transfer\ProductLabelProductAbstractTransfer> $productLabelProductAbstractTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductLabelProductAbstractTransfer>
     */
    public function mapProductLabelProductAbstractEntitiesToProductLabelProductTransfers(
        ObjectCollection $productLabelProductAbstractEntities,
        array $productLabelProductAbstractTransfers
    ): array {
        foreach ($productLabelProductAbstractEntities as $productLabelProductAbstractEntity) {
            $productLabelProductAbstractTransfers[] = $this->mapProductLabelProductAbstractEntityToProductLabelProductTransfer(
                $productLabelProductAbstractEntity,
                new ProductLabelProductAbstractTransfer(),
            );
        }

        return $productLabelProductAbstractTransfers;
    }

    /**
     * @param \Orm\Zed\ProductLabel\Persistence\Base\SpyProductLabelProductAbstract $productLabelProductAbstractEntity
     * @param \Generated\Shared\Transfer\ProductLabelProductAbstractTransfer $productLabelProductAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractTransfer
     */
    protected function mapProductLabelProductAbstractEntityToProductLabelProductTransfer(
        SpyProductLabelProductAbstract $productLabelProductAbstractEntity,
        ProductLabelProductAbstractTransfer $productLabelProductAbstractTransfer
    ): ProductLabelProductAbstractTransfer {
        return $productLabelProductAbstractTransfer->fromArray($productLabelProductAbstractEntity->toArray(), true);
    }
}
