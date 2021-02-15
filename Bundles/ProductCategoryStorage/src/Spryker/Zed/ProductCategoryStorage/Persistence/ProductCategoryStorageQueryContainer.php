<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Persistence;

use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStoragePersistenceFactory getFactory()
 */
class ProductCategoryStorageQueryContainer extends AbstractQueryContainer implements ProductCategoryStorageQueryContainerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productCategoryIds
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function queryProductCategoryByProductCategoryIds($productCategoryIds): SpyProductCategoryQuery
    {
        return $this->getFactory()
            ->getProductCategoryQueryContainer()
            ->queryProductCategoryMappings()
            ->filterByIdProductCategory_In($productCategoryIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\ProductCategoryStorage\Persistence\SpyProductAbstractCategoryStorageQuery
     */
    public function queryProductAbstractCategoryStorageByIds(array $productAbstractIds)
    {
        return $this
            ->getFactory()
            ->createProductAbstractCategoryStoragePropelQuery()
            ->filterByFkProductAbstract_In($productAbstractIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $categoryIds
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function queryProductAbstractIdsByCategoryIds(array $categoryIds)
    {
        return $this->getFactory()
            ->getProductCategoryQueryContainer()
            ->queryProductCategoryMappings()
            ->filterByFkCategory_In($categoryIds)
            ->select(SpyProductCategoryTableMap::COL_FK_PRODUCT_ABSTRACT);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $nodeIds
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryCategoryIdsByNodeIds(array $nodeIds)
    {
        return $this->getFactory()
            ->getCategoryQueryContainer()
            ->queryAllCategoryNodes()
            ->filterByIdCategoryNode_In($nodeIds)
            ->select(SpyCategoryNodeTableMap::COL_FK_CATEGORY);
    }
}
