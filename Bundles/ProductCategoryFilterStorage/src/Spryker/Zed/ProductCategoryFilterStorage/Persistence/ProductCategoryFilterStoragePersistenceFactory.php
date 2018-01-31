<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterStorage\Persistence;

use Orm\Zed\ProductCategoryFilterStorage\Persistence\SpyProductCategoryFilterStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductCategoryFilterStorage\ProductCategoryFilterStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductCategoryFilterStorage\ProductCategoryFilterStorageConfig getConfig()
 * @method \Spryker\Zed\ProductCategoryFilterStorage\Persistence\ProductCategoryFilterStorageQueryContainerInterface getQueryContainer()
 */
class ProductCategoryFilterStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductCategoryFilterStorage\Persistence\SpyProductCategoryFilterStorageQuery
     */
    public function createSpyProductCategoryFilterStorageQuery()
    {
        return SpyProductCategoryFilterStorageQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductCategoryFilterStorage\Dependency\QueryContainer\ProductCategoryFilterStorageToProductCategoryFilterQueryContainerInterface
     */
    public function getProductCategoryFilterQuery()
    {
        return $this->getProvidedDependency(ProductCategoryFilterStorageDependencyProvider::QUERY_CONTAINER_PRODUCT_CATEGORY_FILTER);
    }
}
