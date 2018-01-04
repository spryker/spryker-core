<?php

namespace Spryker\Zed\ProductCategoryFilterStorage\Persistence;

use Orm\Zed\ProductCategoryFilterStorage\Persistence\SpyProductCategoryFilterStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductCategoryFilterStorage\Dependency\QueryContainer\ProductCategoryFilterStorageToProductCategoryFilterQueryContainerInterface;
use Spryker\Zed\ProductCategoryFilterStorage\ProductCategoryFilterStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductCategoryFilterStorage\ProductCategoryFilterStorageConfig getConfig()
 * @method \Spryker\Zed\ProductCategoryFilterStorage\Persistence\ProductCategoryFilterStorageQueryContainer getQueryContainer()
 */
class ProductCategoryFilterStoragePersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return SpyProductCategoryFilterStorageQuery
     */
    public function createSpyProductCategoryFilterStorageQuery()
    {
        return SpyProductCategoryFilterStorageQuery::create();
    }

    /**
     * @return ProductCategoryFilterStorageToProductCategoryFilterQueryContainerInterface
     */
    public function getProductCategoryFilterQuery()
    {
        return $this->getProvidedDependency(ProductCategoryFilterStorageDependencyProvider::QUERY_CONTAINER_PRODUCT_CATEGORY_FILTER);
    }
}
