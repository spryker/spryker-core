<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReview\Communication\Controller;

use Generated\Shared\Transfer\ProductReviewTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\ProductReview\Business\ProductReviewFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductReviewTransfer
     */
    public function submitCustomerReviewAction(ProductReviewTransfer $productReviewTransfer)
    {
        $productReviewTransfer = $this->getFacade()
            ->createProductReview($productReviewTransfer);

        return $productReviewTransfer;
    }

}
