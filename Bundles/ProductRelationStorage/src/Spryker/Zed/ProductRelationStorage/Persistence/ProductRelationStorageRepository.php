<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationStorage\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationProductAbstractTableMap;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationTableMap;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationTypeTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductRelationStorage\Persistence\ProductRelationStoragePersistenceFactory getFactory()
 */
class ProductRelationStorageRepository extends AbstractRepository implements ProductRelationStorageRepositoryInterface
{
    /**
     * @param int[] $relationIds
     *
     * @return array
     */
    public function getProductRelationsWithProductAbstractByIdRelationIn(array $relationIds): array
    {
        return $this->getFactory()
            ->getProductRelationProductAbstractQuery()
            ->filterByFkProductRelation_In($relationIds)
            ->joinWithSpyProductRelation()
            ->useSpyProductRelationQuery()
                ->joinWithSpyProductRelationType()
            ->endUse()
            ->joinWithSpyProductAbstract()
            ->useSpyProductAbstractQuery()
                ->joinWithSpyProductAbstractLocalizedAttributes()
            ->endUse()
            ->select([
                SpyProductRelationTableMap::COL_ID_PRODUCT_RELATION,
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
                SpyProductAbstractLocalizedAttributesTableMap::COL_FK_LOCALE,
                SpyProductRelationProductAbstractTableMap::COL_ORDER,
                SpyProductRelationTypeTableMap::COL_KEY,
            ])
            ->find()
            ->getData();
    }
}
