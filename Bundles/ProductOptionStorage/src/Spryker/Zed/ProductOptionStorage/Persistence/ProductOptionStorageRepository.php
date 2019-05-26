<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionStorage\Persistence;

use Orm\Zed\ProductOption\Persistence\Map\SpyProductAbstractProductOptionGroupTableMap;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionGroupTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductOptionStorage\Persistence\ProductOptionStoragePersistenceFactory getFactory()
 */
class ProductOptionStorageRepository extends AbstractRepository implements ProductOptionStorageRepositoryInterface
{
    /**
     * @param int[] $productAbstractIds
     *
     * @return array
     */
    public function getProductOptionGroupStatusesByProductAbstractIds($productAbstractIds): array
    {
        return $this->getFactory()
            ->getProductAbstractProductOptionGroupPropelQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->joinSpyProductOptionGroup()
            ->select([
                SpyProductAbstractProductOptionGroupTableMap::COL_FK_PRODUCT_ABSTRACT,
                SpyProductOptionGroupTableMap::COL_ACTIVE,
                SpyProductOptionGroupTableMap::COL_NAME,
            ])
            ->find()
            ->toArray();
    }
}
