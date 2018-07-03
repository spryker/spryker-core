<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationStorage\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductRelationStorage\Persistence\ProductRelationStoragePersistenceFactory getFactory()
 */
class ProductRelationStorageQueryContainer extends AbstractQueryContainer implements ProductRelationStorageQueryContainerInterface
{
    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\ProductRelationStorage\Persistence\SpyProductAbstractRelationStorageQuery
     */
    public function queryProductAbstractRelationStorageByIds(array $productAbstractIds)
    {
        return $this
            ->getFactory()
            ->createSpyProductAbstractRelationStorageQuery()
            ->filterByFkProductAbstract_In($productAbstractIds);
    }

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributesQuery
     */
    public function queryProductAbstractLocalizedByIds(array $productAbstractIds)
    {
        return $this->getFactory()
            ->getProductQueryContainer()
            ->queryAllProductAbstractLocalizedAttributes()
            ->joinWithLocale()
            ->joinWithSpyProductAbstract()
            ->filterByFkProductAbstract_In($productAbstractIds);
    }

    /**
     * @api
     *
     * @param int $idProductRelation
     * @param int $idLocale
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryProductRelationWithProductAbstractByIdRelationAndLocale($idProductRelation, $idLocale)
    {
        return $this->getFactory()
            ->getProductRelationQuery()
            ->queryProductRelationWithProductAbstractByIdRelationAndLocale($idProductRelation, $idLocale);
    }

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery
     */
    public function queryProductRelations(array $productAbstractIds)
    {
        return $this->getFactory()
            ->getProductRelationQuery()
            ->queryAllProductRelations()
            ->filterByFkProductAbstract_In($productAbstractIds);
    }

    /**
     * @api
     *
     * @param int[] $productRelationIds
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery
     */
    public function queryProductRelationsByIds(array $productRelationIds)
    {
        return $this->getFactory()
            ->getProductRelationQuery()
            ->queryAllProductRelations()
            ->filterByIdProductRelation_In($productRelationIds);
    }
}
