<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageStorage\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery;
use Propel\Runtime\ActiveQuery\Criteria;
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
     * @param int[] $productFks
     *
     * @return \Generated\Shared\Transfer\SpyProductImageSetEntityTransfer[]
     */
    public function getProductImageSetsByFkProductIn(array $productFks): array
    {
        $productImageSetsQuery = $this->getFactory()
            ->getProductImageSetQuery()
            ->innerJoinWithSpyLocale()
            ->innerJoinWithSpyProductImageSetToProductImage()
            ->useSpyProductImageSetToProductImageQuery()
                ->innerJoinWithSpyProductImage()
            ->endUse()
            ->filterByFkProduct_In($productFks);

        $productImageSetsQuery = $this->sortProductImageSetToProductImageQuery($productImageSetsQuery);

        return $this->buildQueryFromCriteria($productImageSetsQuery)->find();
    }

    /**
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\SpyProductImageSetEntityTransfer[]
     */
    public function getDefaultConcreteProductImageSetsByFkProductIn(array $productIds): array
    {
        $productImageSetsQuery = $this->getFactory()
            ->getProductImageSetQuery()
            ->innerJoinWithSpyProductImageSetToProductImage()
            ->useSpyProductImageSetToProductImageQuery()
                ->innerJoinWithSpyProductImage()
            ->endUse()
            ->filterByFkProduct_In($productIds)
            ->filterByFkLocale(null, Criteria::ISNULL);

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

        $productImageSetsQuery = $this->sortProductImageSetToProductImageQuery($productImageSetsQuery);

        return $this->buildQueryFromCriteria($productImageSetsQuery)->find();
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\SpyProductImageSetEntityTransfer[]
     */
    public function getDefaultAbstractProductImageSetsByIdAbstractProductIn(array $productAbstractIds): array
    {
        $productImageSetsQuery = $this->getFactory()
            ->getProductImageSetQuery()
            ->innerJoinWithSpyProductImageSetToProductImage()
            ->useSpyProductImageSetToProductImageQuery()
                ->innerJoinWithSpyProductImage()
            ->endUse()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->filterByFkLocale(null, Criteria::ISNULL);

        return $this->buildQueryFromCriteria($productImageSetsQuery)->find();
    }

    /**
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery $productImageSetToProductImageQuery
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    protected function sortProductImageSetToProductImageQuery(
        SpyProductImageSetQuery $productImageSetToProductImageQuery
    ): SpyProductImageSetQuery {
        $productImageSetToProductImageQuery->useSpyProductImageSetToProductImageQuery()
                ->orderBySortOrder()
                ->orderByIdProductImageSetToProductImage()
            ->endUse();

        return $productImageSetToProductImageQuery;
    }
}
