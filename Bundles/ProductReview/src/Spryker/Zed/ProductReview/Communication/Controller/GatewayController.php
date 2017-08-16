<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReview\Communication\Controller;

use Generated\Shared\Transfer\ProductReviewRequestTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\ProductReview\Business\ProductReviewFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductReview\Communication\ProductReviewCommunicationFactory getFactory()
 */
class GatewayController extends AbstractGatewayController
{

    /**
     * @param \Generated\Shared\Transfer\ProductReviewRequestTransfer $productReviewRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductReviewTransfer
     */
    public function submitCustomerReviewAction(ProductReviewRequestTransfer $productReviewRequestTransfer)
    {
        $productReviewTransfer = $this->getFactory()
            ->createCustomerReviewSubmitMapper()
            ->mapRequestTransfer($productReviewRequestTransfer);

        $productReviewTransfer = $this->getFacade()
            ->createProductReview($productReviewTransfer);

        return $productReviewTransfer;
    }

}
