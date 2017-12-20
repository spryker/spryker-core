<?php

namespace Spryker\Zed\ProductReviewStorage\Persistence;

use Orm\Zed\ProductReviewStorage\Persistence\SpyProductAbstractReviewStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductReviewStorage\Dependency\QueryContainer\ProductReviewStorageToProductReviewQueryContainerInterface;
use Spryker\Zed\ProductReviewStorage\ProductReviewStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductReviewStorage\ProductReviewStorageConfig getConfig()
 * @method \Spryker\Zed\ProductReviewStorage\Persistence\ProductReviewStorageQueryContainer getQueryContainer()
 */
class ProductReviewStoragePersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return SpyProductAbstractReviewStorageQuery
     */
    public function createSpyProductReviewStorageQuery()
    {
        return SpyProductAbstractReviewStorageQuery::create();
    }

    /**
     * @return ProductReviewStorageToProductReviewQueryContainerInterface
     */
    public function getProductReviewQuery()
    {
        return $this->getProvidedDependency(ProductReviewStorageDependencyProvider::QUERY_CONTAINER_PRODUCT_REVIEW);
    }
}
