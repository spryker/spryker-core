<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewGui\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductReviewGui\ProductReviewGuiDependencyProvider;

/**
 * @method \Spryker\Zed\ProductReviewGui\ProductReviewGuiConfig getConfig()
 * @method \Spryker\Zed\ProductReviewGui\Persistence\ProductReviewGuiQueryContainer getQueryContainer()
 */
class ProductReviewGuiPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\ProductReviewGui\Dependency\QueryContainer\ProductReviewGuiToProductReviewInterface
     */
    public function getProductReviewQueryContainer()
    {
        return $this->getProvidedDependency(ProductReviewGuiDependencyProvider::QUERY_CONTAINER_PRODUCT_REVIEW);
    }
}
