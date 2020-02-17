<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductSelectorTransfer;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageTableMap;

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
}
