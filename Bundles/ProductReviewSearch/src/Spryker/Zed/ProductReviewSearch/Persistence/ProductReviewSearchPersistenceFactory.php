<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewSearch\Persistence;

use Orm\Zed\ProductReview\Persistence\SpyProductReviewQuery;
use Orm\Zed\ProductReviewSearch\Persistence\SpyProductReviewSearchQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductReviewSearch\ProductReviewSearchDependencyProvider;

/**
 * @method \Spryker\Zed\ProductReviewSearch\ProductReviewSearchConfig getConfig()
 * @method \Spryker\Zed\ProductReviewSearch\Persistence\ProductReviewSearchQueryContainerInterface getQueryContainer()
 */
class ProductReviewSearchPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductReviewSearch\Persistence\SpyProductReviewSearchQuery
     */
    public function createSpyProductReviewSearchQuery()
    {
        return SpyProductReviewSearchQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductReviewSearch\Dependency\QueryContainer\ProductReviewSearchToProductReviewQueryContainerInterface
     */
    public function getProductReviewQuery()
    {
        return $this->getProvidedDependency(ProductReviewSearchDependencyProvider::QUERY_CONTAINER_PRODUCT_REVIEW);
    }

    /**
     * @return \Orm\Zed\ProductReview\Persistence\SpyProductReviewQuery
     */
    public function getPropelProductReviewQuery(): SpyProductReviewQuery
    {
        return $this->getProvidedDependency(ProductReviewSearchDependencyProvider::PROPEL_QUERY_PRODUCT_REVIEW);
    }
}
