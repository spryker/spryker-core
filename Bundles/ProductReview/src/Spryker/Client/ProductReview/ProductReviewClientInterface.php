<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReview;

use Generated\Shared\Transfer\ProductReviewTransfer;

interface ProductReviewClientInterface
{

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductReviewTransfer
     */
    public function submitCustomerReview(ProductReviewTransfer $productReviewTransfer);

}
