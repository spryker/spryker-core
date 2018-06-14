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
     * @api
     *
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
     * @param int $productAbstractId
     *
     * @return \Generated\Shared\Transfer\SpyProductEntityTransfer[]
     */
    public function findPackagingProductsByAbstractId(int $productAbstractId): array
    {
        if (!$productAbstractId) {
            return [];
        }

        $query = $this->getFactory()
            ->getProductQueryContainer()
            ->queryProduct()
                ->filterByFkProductAbstract($productAbstractId)
            ->where(sprintf(
                "%s = true",
                SpyProductTableMap::COL_IS_ACTIVE
            ))
            ->joinWithSpyProductPackagingUnit()
            ->useSpyProductPackagingUnitQuery()
                ->leftJoinWithSpyProductPackagingUnitAmount()
                ->leftJoinWithProductPackagingUnitType()
            ->endUse();

        return $this->buildQueryFromCriteria($query)->find();
    }
}
