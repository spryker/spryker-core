<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Persistence\Mapper;

use Generated\Shared\Transfer\ProductAlternativeCollectionTransfer;
use Generated\Shared\Transfer\ProductAlternativeListItemTransfer;
use Generated\Shared\Transfer\ProductAlternativeTransfer;
use Generated\Shared\Transfer\SpyProductAlternativeEntityTransfer;
use Orm\Zed\ProductAlternative\Persistence\SpyProductAlternative;
use Spryker\Shared\ProductAlternative\ProductAlternativeConstants;

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
     * @param \Generated\Shared\Transfer\SpyProductAlternativeEntityTransfer $productAlternativeEntityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function mapSpyProductAlternativeEntityTransferToTransfer(
        SpyProductAlternativeEntityTransfer $productAlternativeEntityTransfer
    ): ProductAlternativeTransfer {
        $productAlternativeTransfer = (new ProductAlternativeTransfer())
            ->fromArray($productAlternativeEntityTransfer->toArray(), true);

        return $productAlternativeTransfer
            ->setIdProduct($productAlternativeEntityTransfer->getFkProduct())
            ->setIdProductAbstractAlternative($productAlternativeEntityTransfer->getFkProductAbstractAlternative())
            ->setIdProductConcreteAlternative($productAlternativeEntityTransfer->getFkProductConcreteAlternative());
    }

    /**
     * @param array $productAlternatives
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeCollectionTransfer
     */
    public function hydrateProductAlternativeCollectionWithProductAlternatives(array $productAlternatives): ProductAlternativeCollectionTransfer
    {
        $productAlternativeCollectionTransfer = new ProductAlternativeCollectionTransfer();

        foreach ($productAlternatives as $productAlternative) {
            $productAlternativeCollectionTransfer->addProductAlternative(
                $this->mapSpyProductAlternativeEntityTransferToTransfer($productAlternative)
            );
        }

        return $productAlternativeCollectionTransfer;
    }

    /**
     * @param array $productAbstractData
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListItemTransfer
     */
    public function mapProductAbstractDataToProductAlternativeListItemTransfer(array $productAbstractData): ProductAlternativeListItemTransfer
    {
        return $this->mapProductDataToProductAlternativeListItemTransfer($productAbstractData)
            ->setType(ProductAlternativeConstants::FIELD_PRODUCT_TYPE_ABSTRACT);
    }

    /**
     * @param array $productConcreteData
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListItemTransfer
     */
    public function mapProductConcreteDataToProductAlternativeListItemTransfer(array $productConcreteData): ProductAlternativeListItemTransfer
    {
        return $this->mapProductDataToProductAlternativeListItemTransfer($productConcreteData)
            ->setType(ProductAlternativeConstants::FIELD_PRODUCT_TYPE_ABSTRACT);
    }

    /**
     * @param array $productData
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListItemTransfer
     */
    protected function mapProductDataToProductAlternativeListItemTransfer(array $productData): ProductAlternativeListItemTransfer
    {
        return (new ProductAlternativeListItemTransfer())
            ->setIdProductAlternative($productData[ProductAlternativeConstants::COL_ID])
            ->setName($productData[ProductAlternativeConstants::COL_NAME])
            ->setSku($productData[ProductAlternativeConstants::COL_SKU])
            ->setCategories(
                explode(
                    ProductAlternativeConstants::COL_SEPARATOR_CATEGORIES,
                    $productData[ProductAlternativeConstants::COL_CATEGORIES]
                )
            )
            ->setStatus($productData[ProductAlternativeConstants::COL_STATUS]);
    }
}
