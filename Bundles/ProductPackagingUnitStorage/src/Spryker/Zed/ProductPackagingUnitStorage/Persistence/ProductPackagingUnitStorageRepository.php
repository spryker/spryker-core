<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitStorageTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStoragePersistenceFactory getFactory()
 */
class ProductPackagingUnitStorageRepository extends AbstractRepository implements ProductPackagingUnitStorageRepositoryInterface
{
    /**
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\SpyProductPackagingUnitStorageEntityTransfer[]
     */
    public function findProductPackagingUnitStorageEntitiesByProductConcreteIds(array $productConcreteIds): array
    {
        if ($productConcreteIds === []) {
            return [];
        }

        $query = $this->getFactory()
            ->createProductPackagingUnitStorageQuery()
            ->filterByFkProduct_In($productConcreteIds);

        return $this->buildQueryFromCriteria($query)->find();
    }

    /**
     * @module ProductPackagingUnit
     * @module Product
     *
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitStorageTransfer[]
     */
    public function findPackagingProductsByProductConcreteIds(array $productConcreteIds): array
    {
        $productPackagingUnitStorageTransfers = [];
        if ($productConcreteIds === []) {
            return $productPackagingUnitStorageTransfers;
        }

        $query = $this->getFactory()
            ->getProductPackagingUnitQuery()
            ->filterByFkProduct_In($productConcreteIds)
            ->innerJoin('SpyProductPackagingUnit.Product Product')
            ->innerJoinWithLeadProduct()
            ->innerJoinWithProductPackagingUnitType()
            ->where('Product.is_active = ?', true);

        $productPackagingUnitEntityTransfers = $this->buildQueryFromCriteria($query)->find();

        foreach ($productPackagingUnitEntityTransfers as $productPackagingUnitEntityTransfer) {
            $productPackagingUnitStorageTransfers[] = $this->getFactory()
                ->createProductPackagingUnitStorageMapper()
                ->mapProductPackagingUnitStorageEntityTransferToStorageTransfer(
                    $productPackagingUnitEntityTransfer,
                    new ProductPackagingUnitStorageTransfer()
                );
        }

        return $productPackagingUnitStorageTransfers;
    }

    /**
     * @module ProductPackagingUnit
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer[]
     */
    public function findFilteredProductPackagingUnit(FilterTransfer $filterTransfer, array $productConcreteIds = []): array
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
     * @return \Generated\Shared\Transfer\SpyProductPackagingUnitStorageEntityTransfer[]
     */
    public function findFilteredProductPackagingUnitStorageEntityTransfers(FilterTransfer $filterTransfer, array $productConcreteIds = []): array
    {
        $query = $this->getFactory()->createProductPackagingUnitStorageQuery();

        if ($productConcreteIds !== []) {
            $query->filterByFkProduct_In($productConcreteIds);
        }

        return $this->buildQueryFromCriteria($query, $filterTransfer)->find();
    }
}
