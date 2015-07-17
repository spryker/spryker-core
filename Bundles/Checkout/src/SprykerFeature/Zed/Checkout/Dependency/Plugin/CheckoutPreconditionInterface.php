<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Checkout\Dependency\Plugin;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;

interface CheckoutPreconditionInterface
{

    /**
     * @param CheckoutRequestTransfer $checkoutRequest
     * @param CheckoutResponseTransfer $checkoutResponse
     */
    public function checkCondition(CheckoutRequestTransfer $checkoutRequest, CheckoutResponseTransfer $checkoutResponse);

}
