<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageStorage\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductImageStorage\Persistence\ProductImageStoragePersistenceFactory getFactory()
 */
class ProductImageStorageRepository extends AbstractRepository implements ProductImageStorageRepositoryInterface
{
    /**
     * @param array $productIds
     *
     * @return array
     */
    public function getProductLocalizedAttributesWithProductByIdProductIn(array $productIds): array
    {
        return $this->getFactory()
            ->getProductLocalizedAttributesQuery()
            ->select([
                SpyProductLocalizedAttributesTableMap::COL_ID_PRODUCT_ATTRIBUTES,
                SpyProductLocalizedAttributesTableMap::COL_FK_PRODUCT,
                SpyProductLocalizedAttributesTableMap::COL_FK_LOCALE,
                SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT,
            ])
            ->innerJoinWithSpyProduct()
            ->filterByFkProduct_In($productIds)
            ->find()
            ->toArray();
    }

    /**
     * @param array $productFks
     * @param array $productAbstractFks
     *
     * @return \Generated\Shared\Transfer\SpyProductImageSetEntityTransfer[]
     */
    public function getProductImageSetsByFkProductInOrFkAbstractProductIn(array $productFks, array $productAbstractFks): array
    {
        $productImageSetsQuery = $this->getFactory()
            ->getProductImageSetQuery()
            ->innerJoinWithSpyLocale()
            ->innerJoinWithSpyProductImageSetToProductImage()
            ->useSpyProductImageSetToProductImageQuery()
                ->innerJoinWithSpyProductImage()
            ->endUse()
            ->filterByFkProduct_In($productFks)
            ->_or()
            ->filterByFkProductAbstract_In($productAbstractFks);

        return $this->buildQueryFromCriteria($productImageSetsQuery)->find();
    }

    /**
     * @param array $productAbstractFks
     *
     * @return \Generated\Shared\Transfer\SpyProductImageSetEntityTransfer[]
     */
    public function getProductImageSetsByFkAbstractProductIn(array $productAbstractFks): array
    {
        $productImageSetsQuery = $this->getFactory()
            ->getProductImageSetQuery()
            ->innerJoinWithSpyLocale()
            ->innerJoinWithSpyProductImageSetToProductImage()
            ->useSpyProductImageSetToProductImageQuery()
                ->innerJoinWithSpyProductImage()
            ->endUse()
            ->filterByFkProductAbstract_In($productAbstractFks);

        return $this->buildQueryFromCriteria($productImageSetsQuery)->find();
    }
}
