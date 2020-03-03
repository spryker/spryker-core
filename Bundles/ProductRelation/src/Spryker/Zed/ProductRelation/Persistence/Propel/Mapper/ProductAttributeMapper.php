<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductAttributeKeyTransfer;
use Orm\Zed\Product\Persistence\SpyProductAttributeKey;
use Propel\Runtime\Collection\ObjectCollection;

class ProductAttributeMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Product\Persistence\SpyProductAttributeKey[] $productAttributeKeyEntities
     * @param \Generated\Shared\Transfer\ProductAttributeKeyTransfer[] $productAttributeKeyTransfers
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeyTransfer[]
     */
    public function mapProductAttributeKeyEntitiesToProductAttributeKeyTransfers(
        ObjectCollection $productAttributeKeyEntities,
        array $productAttributeKeyTransfers
    ): array {
        foreach ($productAttributeKeyEntities as $productAttributeKeyEntity) {
            $productAttributeKeyTransfers[] = $this->mapProductAttributeKeyEntityToProductAttributeKeyTransfer(
                $productAttributeKeyEntity,
                new ProductAttributeKeyTransfer()
            );
        }

        return $productAttributeKeyTransfers;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAttributeKey $productAttributeKeyEntity
     * @param \Generated\Shared\Transfer\ProductAttributeKeyTransfer $productAttributeKeyTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeyTransfer
     */
    public function mapProductAttributeKeyEntityToProductAttributeKeyTransfer(
        SpyProductAttributeKey $productAttributeKeyEntity,
        ProductAttributeKeyTransfer $productAttributeKeyTransfer
    ): ProductAttributeKeyTransfer {
        return $productAttributeKeyTransfer->fromArray($productAttributeKeyEntity->toArray(), true);
    }
}
