<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStoragePersistenceFactory getFactory()
 */
class ProductPackagingUnitStorageRepository extends AbstractRepository implements ProductPackagingUnitStorageRepositoryInterface
{
    /**
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\ProductPackagingUnitStorage\Persistence\SpyProductAbstractPackagingStorage[]
     */
    public function findProductAbstractPackagingStorageEntitiesByProductAbstractIds(array $productAbstractIds): array
    {
        if (!$productAbstractIds) {
            return [];
        }

        $query = $this->getFactory()
            ->createSpyProductAbstractPackagingStorageQuery()
            ->filterByFkProductAbstract_In($productAbstractIds);

        return $this->buildQueryFromCriteria($query)->find();
    }

    /**
     * @module ProductPackagingUnit
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\SpyProductEntityTransfer[]
     */
    public function findPackagingProductsByProductAbstractId(int $idProductAbstract): array
    {
        if (!$idProductAbstract) {
            return [];
        }

        $query = $this->getFactory()
            ->getSpyProductQuery()
            ->filterByFkProductAbstract($idProductAbstract)
            ->filterByIsActive(true)
            ->innerJoinWithSpyProductAbstract()
            ->useSpyProductAbstractQuery()
                ->leftJoinWithSpyProductPackagingLeadProduct()
            ->endUse()
            ->innerJoinWithSpyProductPackagingUnit()
            ->useSpyProductPackagingUnitQuery()
                ->leftJoinWithSpyProductPackagingUnitAmount()
                ->innerJoinWithProductPackagingUnitType()
            ->endUse()
            ->orderByCreatedAt();

        return $this->buildQueryFromCriteria($query)->find();
    }

    /**
     * @return \Orm\Zed\ProductPackagingUnitStorage\Persistence\SpyProductAbstractPackagingStorage[]
     */
    public function findAllProductAbstractPackagingStorageEntities(): array
    {
        $query = $this->getFactory()->createSpyProductAbstractPackagingStorageQuery();

        return $this->buildQueryFromCriteria($query)->find();
    }

    /**
     * @module ProductPackagingUnit
     *
     * @return int[]
     */
    public function findProductAbstractIdsWithProductPackagingUnit(): array
    {
        return $this->getFactory()
            ->getSpyProductQuery()
            ->innerJoinWithSpyProductPackagingUnit()
            ->select([SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT])
            ->distinct()
            ->find()
            ->toArray();
    }
}
