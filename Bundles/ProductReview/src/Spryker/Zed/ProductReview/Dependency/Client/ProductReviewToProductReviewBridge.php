<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReview\Dependency\Client;

use Spryker\Client\ProductReview\ProductReviewClientInterface;

class ProductReviewToProductReviewBridge implements ProductReviewToProductReviewInterface
{

    /**
     * @var ProductReviewClientInterface
     */
    protected $productReviewClient;

    /**
     * @param ProductReviewClientInterface $productReviewClient
     */
    public function __construct($productReviewClient)
    {
        $this->productReviewClient = $productReviewClient;
    }

    /**
     * @return int
     */
    public function getMaximumRating()
    {
        return $this->productReviewClient->getMaximumRating();
    }

}
