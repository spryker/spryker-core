<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Persistence;

use Orm\Zed\ProductPackagingUnit\Persistence\Map\SpyProductPackagingUnitTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStoragePersistenceFactory getFactory()
 */
class ProductPackagingUnitStorageQueryContainer extends AbstractQueryContainer implements ProductPackagingUnitStorageQueryContainerInterface
{
    /**
     * @api
     *
     * @param int $productAbstractId
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryLeadProductByAbstractId(int $productAbstractId)
    {
        return $this->getFactory()
            ->getProductQueryContainer()
            ->queryProduct()
            ->filterByFkProductAbstract($productAbstractId)
            ->innerJoinSpyProductPackagingLeadProduct()
            ->useSpyProductPackagingUnitQuery()
                ->where(sprintf(
                    "%s = %s",
                    SpyProductPackagingUnitTableMap::COL_HAS_LEAD_PRODUCT,
                    1
                ))
            ->endUse();
    }

    /**
     * @api
     *
     * @param int $productAbstractId
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryPackageProductsByAbstractId(int $productAbstractId)
    {
        return $this->getFactory()
            ->getProductQueryContainer()
            ->queryProduct()
            ->filterByFkProductAbstract($productAbstractId)
            ->useSpyProductPackagingUnitQuery()
                ->where(sprintf(
                    "%s = %s",
                    SpyProductPackagingUnitTableMap::COL_HAS_LEAD_PRODUCT,
                    0
                ))
                ->innerJoinProductPackagingUnitType()
                ->joinSpyProductPackagingUnitAmount()
            ->endUse();
    }

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\ProductPackagingUnitStorage\Persistence\SpyProductAbstractPackagingStorageQuery
     */
    public function queryProductAbstractPackagingStorageByProductAbstractIds(array $productAbstractIds)
    {
        return $this->getFactory()
            ->createSpyProductAbstractPackagingStorageQuery()
            ->filterByFkProductAbstract_In($productAbstractIds);
    }
}
