<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Persistence;

use Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery;
use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductCategory\Persistence\Propel\Mapper\CategoryMapper;
use Spryker\Zed\ProductCategory\Persistence\Propel\Mapper\ProductCategoryMapper;

/**
 * @method \Spryker\Zed\ProductCategory\ProductCategoryConfig getConfig()
 * @method \Spryker\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductCategory\Persistence\ProductCategoryRepositoryInterface getRepository()
 */
class ProductCategoryPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function createProductCategoryQuery()
    {
        return SpyProductCategoryQuery::create();
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function createProductAbstractQuery()
    {
        return SpyProductAbstractQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductCategory\Persistence\Propel\Mapper\CategoryMapper
     */
    public function createCategoryMapper(): CategoryMapper
    {
        return new CategoryMapper();
    }

    /**
     * @return \Spryker\Zed\ProductCategory\Persistence\Propel\Mapper\ProductCategoryMapper
     */
    public function createProductCategoryMapper(): ProductCategoryMapper
    {
        return new ProductCategoryMapper(
            $this->createCategoryMapper(),
        );
    }

    /**
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function createCategoryNodeQuery(): SpyCategoryNodeQuery
    {
        return SpyCategoryNodeQuery::create();
    }

    /**
     * @return \Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery
     */
    public function createCategoryClosureTableQuery(): SpyCategoryClosureTableQuery
    {
        return SpyCategoryClosureTableQuery::create();
    }
}
