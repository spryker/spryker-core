<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Checkout\Business\Workflow;

use Generated\Shared\Transfer\CheckoutRequestTransfer;

interface CheckoutWorkflowInterface
{

    /**
     * @param CheckoutRequestTransfer $checkoutRequest
     *
     * @return CheckoutRequestTransfer
     */
    public function requestCheckout(CheckoutRequestTransfer $checkoutRequest);

}
