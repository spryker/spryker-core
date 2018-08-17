<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryResourceAliasStorage\Persistence;

use Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery;
use Orm\Zed\ProductCategoryStorage\Persistence\SpyProductAbstractCategoryStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductCategoryResourceAliasStorage\ProductCategoryResourceAliasStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductCategoryResourceAliasStorage\ProductCategoryResourceAliasStorageConfig getConfig()
 */
class ProductCategoryResourceAliasStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductCategoryStorage\Persistence\SpyProductAbstractCategoryStorageQuery
     */
    public function getProductAbstractCategoryStoragePropelQuery(): SpyProductAbstractCategoryStorageQuery
    {
        return $this->getProvidedDependency(ProductCategoryResourceAliasStorageDependencyProvider::PROPEL_QUERY_PRODUCT_ABSTRACT_CATEGORY_STORAGE);
    }

    /**
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function getProductCategoryPropelQuery(): SpyProductCategoryQuery
    {
        return $this->getProvidedDependency(ProductCategoryResourceAliasStorageDependencyProvider::PROPEL_QUERY_PRODUCT_CATEGORY);
    }
}
