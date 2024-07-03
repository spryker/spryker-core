<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Persistence\Mapper;

use Generated\Shared\Transfer\ProductAttributeKeyCollectionTransfer;
use Generated\Shared\Transfer\ProductAttributeKeyTransfer;
use Propel\Runtime\Collection\Collection;

class ProductAttributeKeyMapper
{
    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\Product\Persistence\SpyProductAttributeKey> $productAttributeKeyEntities
     * @param \Generated\Shared\Transfer\ProductAttributeKeyCollectionTransfer $productAttributeKeyCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeyCollectionTransfer
     */
    public function mapProductAttributeKeyEntitiesToProductAttributeKeyCollection(
        Collection $productAttributeKeyEntities,
        ProductAttributeKeyCollectionTransfer $productAttributeKeyCollectionTransfer
    ): ProductAttributeKeyCollectionTransfer {
        foreach ($productAttributeKeyEntities as $productAttributeKeyEntity) {
            $productAttributeKeyTransfer = (new ProductAttributeKeyTransfer())
                ->fromArray($productAttributeKeyEntity->toArray(), true);

            $productAttributeKeyCollectionTransfer->addProductAttributeKey($productAttributeKeyTransfer);
        }

        return $productAttributeKeyCollectionTransfer;
    }
}
