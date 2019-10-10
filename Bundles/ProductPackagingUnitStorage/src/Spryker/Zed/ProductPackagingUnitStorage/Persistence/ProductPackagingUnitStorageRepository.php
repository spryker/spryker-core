<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
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
     * @return \Generated\Shared\Transfer\SpyProductAbstractPackagingStorageEntityTransfer[]
     */
    public function findAllProductAbstractPackagingStorageEntities(): array
    {
        $query = $this->getFactory()->createSpyProductAbstractPackagingStorageQuery();

        return $this->buildQueryFromCriteria($query)->find();
    }

    /**
     * @module ProductPackagingUnit
     *
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\SpyProductPackagingLeadProductEntityTransfer[]
     */
    public function getProductPackagingLeadProductEntityTransfersByProductAbstractIds(array $productAbstractIds): array
    {
        $query = $this->getFactory()->getProductPackagingLeadProductQuery();

        if ($productAbstractIds !== []) {
            $query->filterByFkProductAbstract_In($productAbstractIds);
        }

        return $this->buildQueryFromCriteria($query)->find();
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\SpyProductAbstractPackagingStorageEntityTransfer[]
     */
    public function findFilteredProductAbstractPackagingUnitStorages(FilterTransfer $filterTransfer, array $productAbstractIds = []): array
    {
        $query = $this->getFactory()->createSpyProductAbstractPackagingStorageQuery();

        if ($productAbstractIds) {
            $query->filterByFkProductAbstract_In($productAbstractIds);
        }

        return $this->buildQueryFromCriteria($query, $filterTransfer)->find();
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\SpyProductPackagingLeadProductEntityTransfer[]
     */
    public function getProductPackagingLeadProductEntityByFilter(FilterTransfer $filterTransfer): array
    {
        $query = $this->getFactory()
            ->getProductPackagingLeadProductQuery()
            ->limit($filterTransfer->getLimit())
            ->offset($filterTransfer->getOffset());

        return $this->buildQueryFromCriteria($query)->find();
    }
}
