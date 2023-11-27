<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductSearchAttributeCollectionTransfer;
use Generated\Shared\Transfer\ProductSearchAttributeTransfer;
use Orm\Zed\ProductSearch\Persistence\Base\SpyProductSearchAttribute;
use Propel\Runtime\Collection\ObjectCollection;

class ProductSearchAttributeMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttribute> $productSearchAttributeEntities
     * @param \Generated\Shared\Transfer\ProductSearchAttributeCollectionTransfer $productSearchAttributeCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSearchAttributeCollectionTransfer
     */
    public function mapProductSearchAttributeEntitiesToProductSearchAttributeCollectionTransfer(
        ObjectCollection $productSearchAttributeEntities,
        ProductSearchAttributeCollectionTransfer $productSearchAttributeCollectionTransfer
    ): ProductSearchAttributeCollectionTransfer {
        foreach ($productSearchAttributeEntities as $productSearchAttributeEntity) {
            $productSearchAttributeTransfer = $this->mapProductSearchAttributeEntityToProductSearchAttributeTransfer(
                $productSearchAttributeEntity,
                new ProductSearchAttributeTransfer(),
            );

            $productSearchAttributeCollectionTransfer->addProductSearchAttribute($productSearchAttributeTransfer);
        }

        return $productSearchAttributeCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\ProductSearch\Persistence\Base\SpyProductSearchAttribute $productSearchAttributeEntity
     * @param \Generated\Shared\Transfer\ProductSearchAttributeTransfer $productSearchAttributeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSearchAttributeTransfer
     */
    protected function mapProductSearchAttributeEntityToProductSearchAttributeTransfer(
        SpyProductSearchAttribute $productSearchAttributeEntity,
        ProductSearchAttributeTransfer $productSearchAttributeTransfer
    ): ProductSearchAttributeTransfer {
        return $productSearchAttributeTransfer
            ->fromArray($productSearchAttributeEntity->toArray(), true)
            ->setKey($productSearchAttributeEntity->getSpyProductAttributeKey()->getKey());
    }
}
