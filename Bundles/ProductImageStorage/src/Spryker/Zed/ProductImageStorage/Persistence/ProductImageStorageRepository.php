<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageStorage\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductImageStorage\Persistence\ProductImageStoragePersistenceFactory getFactory()
 */
class ProductImageStorageRepository extends AbstractRepository implements ProductImageStorageRepositoryInterface
{
    /**
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\SpyProductLocalizedAttributesEntityTransfer[]
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
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\SpyProductImageSetEntityTransfer[]
     */
    public function getProductImageSetsByIdProductIn(array $productIds): array
    {
        $productImageSetsQuery = $this->getFactory()
            ->getProductImageSetQuery()
            ->innerJoinWithSpyLocale()
            ->innerJoinWithSpyProductImageSetToProductImage()
            ->useSpyProductImageSetToProductImageQuery()
                ->innerJoinWithSpyProductImage()
            ->endUse()
            ->filterByFkProduct_In($productIds);

        return $this->buildQueryFromCriteria($productImageSetsQuery)->find();
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\SpyProductImageSetEntityTransfer[]
     */
    public function getProductImageSetsByIdAbstractProductIn(array $productAbstractIds): array
    {
        $productImageSetsQuery = $this->getFactory()
            ->getProductImageSetQuery()
            ->innerJoinWithSpyLocale()
            ->innerJoinWithSpyProductImageSetToProductImage()
            ->useSpyProductImageSetToProductImageQuery()
                ->innerJoinWithSpyProductImage()
            ->endUse()
            ->filterByFkProductAbstract_In($productAbstractIds);

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
            ->filterByIdProductAbstract_In($productAbstractIds)
            ->filterByFkLocale(null, Criteria::ISNULL);

        return $this->buildQueryFromCriteria($productImageSetsQuery)->find();
    }
}
