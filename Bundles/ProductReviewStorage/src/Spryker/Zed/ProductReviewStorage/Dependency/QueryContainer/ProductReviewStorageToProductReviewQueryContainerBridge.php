<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewStorage\Dependency\QueryContainer;

class ProductReviewStorageToProductReviewQueryContainerBridge implements ProductReviewStorageToProductReviewQueryContainerInterface
{

    /**
     * @var \Spryker\Zed\ProductReview\Persistence\ProductReviewQueryContainerInterface
     */
    protected $productReviewQueryContainer;

    /**
     * @param \Spryker\Zed\ProductReview\Persistence\ProductReviewQueryContainerInterface $productReviewQueryContainer
     */
    public function __construct($productReviewQueryContainer)
    {
        $this->productReviewQueryContainer = $productReviewQueryContainer;
    }

    /**
     * @return \Orm\Zed\ProductReview\Persistence\SpyProductReviewQuery
     */
    public function queryProductReview()
    {
        return $this->productReviewQueryContainer->queryProductReview();
    }

}
