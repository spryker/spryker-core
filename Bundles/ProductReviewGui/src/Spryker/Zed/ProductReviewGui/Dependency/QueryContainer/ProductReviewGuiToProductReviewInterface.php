<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewGui\Dependency\QueryContainer;

use Orm\Zed\ProductReview\Persistence\SpyProductReviewQuery;

interface ProductReviewGuiToProductReviewInterface
{
    /**
     * @return \Orm\Zed\ProductReview\Persistence\SpyProductReviewQuery
     */
    public function queryProductReview(): SpyProductReviewQuery;
}
