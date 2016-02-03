<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Checkout;

use Generated\Shared\Transfer\CheckoutRequestTransfer;

interface CheckoutClientInterface
{

    /**
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $requestInterface
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function requestCheckout(CheckoutRequestTransfer $requestInterface);

}
