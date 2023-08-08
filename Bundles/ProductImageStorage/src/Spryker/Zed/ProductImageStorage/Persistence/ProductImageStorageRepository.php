<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageStorage\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetTableMap;
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
     * @param array<int> $productFks
     *
     * @return array<\Generated\Shared\Transfer\SpyProductImageSetEntityTransfer>
     */
    public function getProductImageSetsByFkProductIn(array $productFks): array
    {
        /** @var \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery $productImageSetsQuery */
        $productImageSetsQuery = $this->getFactory()
            ->getProductImageSetQuery()
            ->innerJoinWithSpyLocale()
            ->innerJoinWithSpyProductImageSetToProductImage()
            ->filterByFkProduct_In($productFks)
            ->useSpyProductImageSetToProductImageQuery()
                ->innerJoinWithSpyProductImage()
            ->endUse();

        $productImageSetsQuery = $this->sortProductImageSetToProductImageQuery($productImageSetsQuery);

        return $this->buildQueryFromCriteria($productImageSetsQuery)->find();
    }

    /**
     * @param array<int> $productIds
     *
     * @return array<\Generated\Shared\Transfer\SpyProductImageSetEntityTransfer>
     */
    public function getDefaultConcreteProductImageSetsByFkProductIn(array $productIds): array
    {
        /** @var \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery $productImageSetsQuery */
        $productImageSetsQuery = $this->getFactory()
            ->getProductImageSetQuery()
            ->innerJoinWithSpyProductImageSetToProductImage()
            ->filterByFkProduct_In($productIds)
            ->filterByFkLocale(null, Criteria::ISNULL)
            ->useSpyProductImageSetToProductImageQuery()
                ->innerJoinWithSpyProductImage()
            ->endUse();

        return $this->buildQueryFromCriteria($productImageSetsQuery)->find();
    }

    /**
     * @param array $productAbstractFks
     *
     * @return array<\Generated\Shared\Transfer\SpyProductImageSetEntityTransfer>
     */
    public function getProductImageSetsByFkAbstractProductIn(array $productAbstractFks): array
    {
        /** @var \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery $productImageSetsQuery */
        $productImageSetsQuery = $this->getFactory()
            ->getProductImageSetQuery()
            ->innerJoinWithSpyLocale()
            ->innerJoinWithSpyProductImageSetToProductImage()
            ->filterByFkProductAbstract_In($productAbstractFks)
            ->useSpyProductImageSetToProductImageQuery()
                ->innerJoinWithSpyProductImage()
            ->endUse();

        $productImageSetsQuery = $this->sortProductImageSetToProductImageQuery($productImageSetsQuery);

        return $this->buildQueryFromCriteria($productImageSetsQuery)->find();
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\SpyProductImageSetEntityTransfer>
     */
    public function getDefaultAbstractProductImageSetsByIdAbstractProductIn(array $productAbstractIds): array
    {
        /** @var \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery $productImageSetsQuery */
        $productImageSetsQuery = $this->getFactory()
            ->getProductImageSetQuery()
            ->innerJoinWithSpyProductImageSetToProductImage()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->filterByFkLocale(null, Criteria::ISNULL)
            ->useSpyProductImageSetToProductImageQuery()
                ->innerJoinWithSpyProductImage()
            ->endUse();

        return $this->buildQueryFromCriteria($productImageSetsQuery)->find();
    }

    /**
     * @module ProductImage
     *
     * @param list<int> $productImageSetIds
     *
     * @return list<int>
     */
    public function getProductAbstractIdsByProductImageSetIds(array $productImageSetIds): array
    {
        return $this->getFactory()
            ->getProductImageSetQuery()
            ->filterByIdProductImageSet_In($productImageSetIds)
            ->select([SpyProductImageSetTableMap::COL_FK_PRODUCT_ABSTRACT])
            ->find()
            ->toArray();
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
