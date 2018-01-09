<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroupStorage\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductGroupStorage\Persistence\ProductGroupStoragePersistenceFactory getFactory()
 */
class ProductGroupStorageQueryContainer extends AbstractQueryContainer implements ProductGroupStorageQueryContainerInterface
{
    /**
     * @api
     *
     * @param array $productGroupIds
     *
     * @return \Orm\Zed\ProductGroup\Persistence\SpyProductAbstractGroupQuery
     */
    public function queryProductAbstractGroupByGroupIds(array $productGroupIds)
    {
        return $this->getFactory()->getProductAbstractGroupQuery()
            ->queryAllProductAbstractGroups()
            ->filterByFkProductGroup_In($productGroupIds)
            ->orderByPosition();
    }

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\ProductGroup\Persistence\SpyProductAbstractGroupQuery
     */
    public function queryProductAbstractGroupByProductAbstractIds(array $productAbstractIds)
    {
        return $this->getFactory()->getProductAbstractGroupQuery()
            ->queryAllProductAbstractGroups()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->orderByPosition();
    }

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\ProductGroupStorage\Persistence\SpyProductAbstractGroupStorageQuery
     */
    public function queryProductAbstractGroupStorageByIds(array $productAbstractIds)
    {
        return $this
            ->getFactory()
            ->createSpyProductAbstractGroupStorageQuery()
            ->filterByFkProductAbstract_In($productAbstractIds);
    }

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributesQuery
     */
    public function queryProductAbstractLocalizedWithGroupByIds(array $productAbstractIds)
    {
        return $this->getFactory()
            ->getProductQueryContainer()
            ->queryAllProductAbstractLocalizedAttributes()
            ->joinWithLocale()
            ->joinWithSpyProductAbstract()
            ->joinWith('SpyProductAbstract.SpyProductAbstractGroup')
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
}
