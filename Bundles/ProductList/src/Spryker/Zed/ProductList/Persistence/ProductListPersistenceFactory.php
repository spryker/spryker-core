<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Persistence;

use Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery;
use Orm\Zed\ProductList\Persistence\SpyProductListCategoryQuery;
use Orm\Zed\ProductList\Persistence\SpyProductListProductConcreteQuery;
use Orm\Zed\ProductList\Persistence\SpyProductListQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductList\ProductListDependencyProvider;

/**
 * @method \Spryker\Zed\ProductList\ProductListConfig getConfig()
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
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function getProductCategoryQuery(): SpyProductCategoryQuery
    {
        return $this->getProvidedDependency(ProductListDependencyProvider::PROPEL_PRODUCT_CATEGORY_QUERY);
    }
}
