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
     * @param int[] $idProductAbstracts
     *
     * @return \Generated\Shared\Transfer\SpyProductAbstractPackagingStorageEntityTransfer[]
     */
    public function findProductAbstractPackagingUnitStorageByProductAbstractIds(array $idProductAbstracts): array
    {
        if (!$idProductAbstracts) {
            return [];
        }

        $query = $this->getFactory()
            ->createSpyProductAbstractPackagingStorageQuery()
            ->filterByFkProductAbstract_In($idProductAbstracts);

        return $this->buildQueryFromCriteria($query)->find();
    }

    /**
     * @module ProductPackagingUnit
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\SpyProductEntityTransfer[]
     */
    public function findPackagingProductsByAbstractId(int $idProductAbstract): array
    {
        if (!$idProductAbstract) {
            return [];
        }

        $query = $this->getFactory()
            ->getProductQueryContainer()
            ->queryProduct()
                ->filterByFkProductAbstract($idProductAbstract)
            ->where(sprintf(
                "%s = true",
                SpyProductTableMap::COL_IS_ACTIVE
            ))
            ->leftJoinWithSpyProductAbstract()
            ->leftJoinWithSpyProductPackagingLeadProduct()
            ->innerJoinWithSpyProductPackagingUnit()
            ->useSpyProductPackagingUnitQuery()
                ->leftJoinWithSpyProductPackagingUnitAmount()
                ->leftJoinWithProductPackagingUnitType()
            ->endUse();

        return $this->buildQueryFromCriteria($query)->find();
    }
}
