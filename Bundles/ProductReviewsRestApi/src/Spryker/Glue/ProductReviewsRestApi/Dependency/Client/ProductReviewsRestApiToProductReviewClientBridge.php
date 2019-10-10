<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductReviewsRestApi\Dependency\Client;

use Generated\Shared\Transfer\ProductReviewSearchRequestTransfer;

class ProductReviewsRestApiToProductReviewClientBridge implements ProductReviewsRestApiToProductReviewClientInterface
{
    /**
     * @var \Spryker\Client\ProductReview\ProductReviewClientInterface
     */
    protected $productReviewClient;

    /**
     * @param \Spryker\Client\ProductReview\ProductReviewClientInterface $productReviewClient
     */
    public function __construct($productReviewClient)
    {
        $this->productReviewClient = $productReviewClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewSearchRequestTransfer $productReviewSearchRequestTransfer
     *
     * @return array
     */
    public function findProductReviewsInSearch(ProductReviewSearchRequestTransfer $productReviewSearchRequestTransfer)
    {
        return $this->productReviewClient->findProductReviewsInSearch($productReviewSearchRequestTransfer);
    }
}
