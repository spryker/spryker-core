<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReview\Persistence;

use Orm\Zed\ProductReview\Persistence\SpyProductReviewQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\ProductReview\Persistence\ProductReviewQueryContainer getQueryContainer()
 * @method \Spryker\Zed\ProductReview\ProductReviewConfig getConfig()
 */
class ProductReviewPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Orm\Zed\ProductReview\Persistence\SpyProductReviewQuery
     */
    public function createProductReviewQuery()
    {
        return SpyProductReviewQuery::create();
    }

}
