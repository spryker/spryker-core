<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Checkout\Service;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;

interface CheckoutClientInterface
{

    /**
     * @param CheckoutRequestTransfer $requestInterface
     *
     * @return CheckoutResponseTransfer
     */
    public function requestCheckout(CheckoutRequestTransfer $requestInterface);

}
