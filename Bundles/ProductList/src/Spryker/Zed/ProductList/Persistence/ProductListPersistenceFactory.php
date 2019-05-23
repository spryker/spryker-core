<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Persistence;

use Orm\Zed\ProductList\Persistence\SpyProductListCategoryQuery;
use Orm\Zed\ProductList\Persistence\SpyProductListProductConcreteQuery;
use Orm\Zed\ProductList\Persistence\SpyProductListQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductList\Persistence\Mapper\ProductListMapper;

/**
 * @method \Spryker\Zed\ProductList\ProductListConfig getConfig()
 * @method \Spryker\Zed\ProductList\Persistence\ProductListEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductList\Persistence\ProductListRepositoryInterface getRepository()
 */
class ProductListPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductList\Persistence\SpyProductListQuery
     */
    public function createProductListQuery(): SpyProductListQuery
    {
        return SpyProductListQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductList\Persistence\SpyProductListCategoryQuery
     */
    public function createProductListCategoryQuery(): SpyProductListCategoryQuery
    {
        return SpyProductListCategoryQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductList\Persistence\SpyProductListProductConcreteQuery
     */
    public function createProductListProductConcreteQuery(): SpyProductListProductConcreteQuery
    {
        return SpyProductListProductConcreteQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductList\Persistence\Mapper\ProductListMapper
     */
    public function createProductListMapper(): ProductListMapper
    {
        return new ProductListMapper();
    }
}
