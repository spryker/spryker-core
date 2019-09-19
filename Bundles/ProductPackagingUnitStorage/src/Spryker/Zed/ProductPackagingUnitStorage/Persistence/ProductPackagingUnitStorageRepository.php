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
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\SpyProductConcretePackagingStorageEntityTransfer[]
     */
    public function findProductConcretePackagingStorageEntitiesByProductConcreteIds(array $productConcreteIds): array
    {
        if (!$productConcreteIds) {
            return [];
        }

        $query = $this->getFactory()
            ->createSpyProductConcretePackagingStorageQuery()
            ->filterByFkProduct_In($productConcreteIds);

        return $this->buildQueryFromCriteria($query)->find();
    }

    /**
     * @module ProductPackagingUnit
     * @module Product
     *
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer[]
     */
    public function findPackagingProductsByProductConcreteIds(array $productConcreteIds): array
    {
        if ($productConcreteIds === []) {
            return [];
        }

        $query = $this->getFactory()
            ->getProductPackagingUnitQuery()
            ->filterByFkProduct_In($productConcreteIds)
            ->innerJoinWithProduct()
            ->useProductQuery('Product')
                ->filterByIsActive(true)
            ->endUse()
            ->joinWithLeadProduct()
            ->innerJoinWithProductPackagingUnitType();

        return $this->buildQueryFromCriteria($query)->find();
    }

    /**
     * @module ProductPackagingUnit
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer[]
     */
    public function findFilteredProductConcretePackagingUnit(FilterTransfer $filterTransfer, array $productConcreteIds = []): array
    {
        $query = $this->getFactory()->getProductPackagingUnitQuery();

        if ($productConcreteIds !== []) {
            $query->filterByFkProduct_In($productConcreteIds);
        }

        return $this->buildQueryFromCriteria($query, $filterTransfer)->find();
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\SpyProductConcretePackagingStorageEntityTransfer[]
     */
    public function findFilteredProductConcretePackagingUnitStorages(FilterTransfer $filterTransfer, array $productConcreteIds = []): array
    {
        $query = $this->getFactory()->createSpyProductConcretePackagingStorageQuery();

        if ($productConcreteIds) {
            $query->filterByFkProduct_In($productConcreteIds);
        }

        return $this->buildQueryFromCriteria($query, $filterTransfer)->find();
    }
}
