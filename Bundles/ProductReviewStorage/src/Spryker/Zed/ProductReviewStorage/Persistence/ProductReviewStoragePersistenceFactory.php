<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewStorage\Persistence;

use Orm\Zed\ProductReviewStorage\Persistence\SpyProductAbstractReviewStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductReviewStorage\ProductReviewStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductReviewStorage\ProductReviewStorageConfig getConfig()
 * @method \Spryker\Zed\ProductReviewStorage\Persistence\ProductReviewStorageQueryContainerInterface getQueryContainer()
 */
class ProductReviewStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductReviewStorage\Persistence\SpyProductAbstractReviewStorageQuery
     */
    public function createSpyProductReviewStorageQuery()
    {
        return SpyProductAbstractReviewStorageQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductReviewStorage\Dependency\QueryContainer\ProductReviewStorageToProductReviewQueryContainerInterface
     */
    public function getProductReviewQuery()
    {
        return $this->getProvidedDependency(ProductReviewStorageDependencyProvider::QUERY_CONTAINER_PRODUCT_REVIEW);
    }
}
