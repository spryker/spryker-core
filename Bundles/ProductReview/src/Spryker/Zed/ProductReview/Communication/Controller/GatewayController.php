<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReview\Communication\Controller;

use Generated\Shared\Transfer\ProductReviewErrorTransfer;
use Generated\Shared\Transfer\ProductReviewRequestTransfer;
use Generated\Shared\Transfer\ProductReviewResponseTransfer;
use Spryker\Shared\ProductReview\Exception\RatingOutOfRangeException;
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
     * @return \Generated\Shared\Transfer\ProductReviewResponseTransfer
     */
    public function submitCustomerReviewAction(ProductReviewRequestTransfer $productReviewRequestTransfer)
    {
        $productReviewTransfer = $this->getFactory()
            ->createCustomerReviewSubmitMapper()
            ->mapRequestTransfer($productReviewRequestTransfer);

        try {
            $productReviewTransfer = $this->getFacade()
                ->createProductReview($productReviewTransfer);

            return (new ProductReviewResponseTransfer())
                ->setIsSuccess(true)
                ->setProductReview($productReviewTransfer);
        } catch (RatingOutOfRangeException $exception) {
            return (new ProductReviewResponseTransfer())
                ->setIsSuccess(false)
                ->addError((new ProductReviewErrorTransfer())->setMessage($exception->getMessage()));
        }
    }
}
