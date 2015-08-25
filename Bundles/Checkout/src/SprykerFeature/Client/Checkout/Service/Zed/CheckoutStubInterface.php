<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Checkout\Service\Zed;

use Generated\Shared\Checkout\CheckoutRequestInterface;
use Generated\Shared\Checkout\CheckoutResponseInterface;

interface CheckoutStubInterface
{

    /**
     * @param CheckoutRequestInterface $transferCheckout
     *
     * @return CheckoutResponseInterface
     */
    public function requestCheckout(CheckoutRequestInterface $transferCheckout);

}
