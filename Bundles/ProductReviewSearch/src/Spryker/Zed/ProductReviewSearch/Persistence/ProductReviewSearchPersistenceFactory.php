<?php

namespace Spryker\Zed\ProductReviewSearch\Persistence;

use Orm\Zed\ProductReviewSearch\Persistence\SpyProductReviewSearchQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductReviewSearch\Dependency\QueryContainer\ProductReviewSearchToProductReviewQueryContainerInterface;
use Spryker\Zed\ProductReviewSearch\ProductReviewSearchDependencyProvider;

/**
 * @method \Spryker\Zed\ProductReviewSearch\ProductReviewSearchConfig getConfig()
 * @method \Spryker\Zed\ProductReviewSearch\Persistence\ProductReviewSearchQueryContainer getQueryContainer()
 */
class ProductReviewSearchPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return SpyProductReviewSearchQuery
     */
    public function createSpyProductReviewSearchQuery()
    {
        return SpyProductReviewSearchQuery::create();
    }

    /**
     * @return ProductReviewSearchToProductReviewQueryContainerInterface
     */
    public function getProductReviewQuery()
    {
        return $this->getProvidedDependency(ProductReviewSearchDependencyProvider::QUERY_CONTAINER_PRODUCT_REVIEW);
    }
}
