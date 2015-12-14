<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Checkout\Zed;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;

interface CheckoutStubInterface
{

    /**
     * @param CheckoutRequestTransfer $transferCheckout
     *
     * @return CheckoutResponseTransfer
     */
    public function requestCheckout(CheckoutRequestTransfer $transferCheckout);

}
