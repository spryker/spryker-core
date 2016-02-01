<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Checkout\Zed;

use Generated\Shared\Transfer\CheckoutRequestTransfer;

interface CheckoutStubInterface
{

    /**
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $transferCheckout
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function requestCheckout(CheckoutRequestTransfer $transferCheckout);

}
