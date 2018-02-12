<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewSearch\Dependency\QueryContainer;

interface ProductReviewSearchToProductReviewQueryContainerInterface
{
    /**
     * @return \Orm\Zed\ProductReview\Persistence\SpyProductReviewQuery
     */
    public function queryProductReview();
}
