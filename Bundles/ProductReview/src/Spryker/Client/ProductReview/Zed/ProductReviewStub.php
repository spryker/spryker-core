<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReview\Zed;

use Generated\Shared\Transfer\ProductReviewRequestTransfer;
use Spryker\Client\ProductReview\Dependency\Client\ProductReviewToZedRequestBridge;

class ProductReviewStub implements ProductReviewStubInterface
{
    /**
     * @var \Spryker\Client\ProductReview\Dependency\Client\ProductReviewToZedRequestBridge
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\ProductReview\Dependency\Client\ProductReviewToZedRequestBridge $zedRequestClient
     */
    public function __construct(ProductReviewToZedRequestBridge $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewRequestTransfer $productReviewRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductReviewResponseTransfer
     */
    public function submitCustomerReview(ProductReviewRequestTransfer $productReviewRequestTransfer)
    {
        /** @var \Generated\Shared\Transfer\ProductReviewResponseTransfer $productReviewRequestTransfer */
        $productReviewRequestTransfer = $this->zedRequestClient->call('/product-review/gateway/submit-customer-review', $productReviewRequestTransfer);

        return $productReviewRequestTransfer;
    }
}
