<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStoragePersistenceFactory getFactory()
 */
class ProductPackagingUnitStorageRepository extends AbstractRepository implements ProductPackagingUnitStorageRepositoryInterface
{
    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\SpyProductAbstractPackagingStorageEntityTransfer[]
     */
    public function findProductAbstractPackagingUnitStorageByProductAbstractIds(array $productAbstractIds): array
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
     * @return \Generated\Shared\Transfer\SpyProductAbstractPackagingStorageEntityTransfer[]
     */
    public function findAllProductAbstractPackagingUnitStorageEntities(): array
    {
        $query = $this->getFactory()->createSpyProductAbstractPackagingStorageQuery();

        return $this->buildQueryFromCriteria($query)->find();
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\SpyProductAbstractPackagingStorageEntityTransfer[]
     */
    public function findProductAbstractPackagingUnitStoragesByOffsetAndLimit(int $offset, int $limit): array
    {
        $query = $this->getFactory()
            ->createSpyProductAbstractPackagingStorageQuery()
            ->offset($offset)
            ->limit($limit);

        return $this->buildQueryFromCriteria($query)->find();
    }
}
