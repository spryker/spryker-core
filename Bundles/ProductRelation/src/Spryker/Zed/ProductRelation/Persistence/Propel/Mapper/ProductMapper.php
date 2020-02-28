<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductRelationRelatedProductTransfer;
use Generated\Shared\Transfer\ProductRelationTransfer;
use Generated\Shared\Transfer\ProductSelectorTransfer;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageTableMap;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelationProductAbstract;
use Propel\Runtime\Collection\ObjectCollection;

class ProductMapper
{
    /**
     * @param array $productArray
     * @param \Generated\Shared\Transfer\ProductSelectorTransfer $productSelectorTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSelectorTransfer
     */
    public function mapProductArrayToProductSelectorTransfer(
        array $productArray,
        ProductSelectorTransfer $productSelectorTransfer
    ): ProductSelectorTransfer {
        $productSelectorTransfer->fromArray($productArray, true);
        $productSelectorTransfer->setIdProductAbstract($productArray[SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT])
            ->setSku($productArray[SpyProductAbstractTableMap::COL_SKU])
            ->setName($productArray[SpyProductAbstractLocalizedAttributesTableMap::COL_NAME])
            ->setDescription($productArray[SpyProductAbstractLocalizedAttributesTableMap::COL_DESCRIPTION])
            ->setPrice($productArray[SpyPriceProductTableMap::COL_PRICE])
            ->setExternalUrlSmall($productArray[SpyProductImageTableMap::COL_EXTERNAL_URL_SMALL]);

        return $productSelectorTransfer;
    }

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

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ProductRelation\Persistence\SpyProductRelationProductAbstract[] $productRelationRelatedProductEntities
     * @param \Generated\Shared\Transfer\ProductRelationRelatedProductTransfer[] $relatedProductTransfers
     *
     * @return \Generated\Shared\Transfer\ProductRelationRelatedProductTransfer[]
     */
    public function mapProductRelationRelatedProductEntitiesToRelatedProductTransfers(
        ObjectCollection $productRelationRelatedProductEntities,
        array $relatedProductTransfers
    ): array {
        foreach ($productRelationRelatedProductEntities as $productRelationRelatedProductEntity) {
            $productAbstractTransfers[] = $this->mapProductRelationProductAbstractEntityToProductRelationRelatedProductTransfer(
                $productRelationRelatedProductEntity,
                new ProductRelationRelatedProductTransfer()
            );
        }

        return $relatedProductTransfers;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function mapProductAbstractEntityToProductAbstractTransfer(
        SpyProductAbstract $productAbstractEntity,
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductAbstractTransfer {
        return $productAbstractTransfer->fromArray($productAbstractEntity->toArray(), true);
    }
}
