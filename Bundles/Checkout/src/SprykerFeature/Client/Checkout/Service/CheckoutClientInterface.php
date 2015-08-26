<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Checkout\Service;

use Generated\Shared\Checkout\CheckoutRequestInterface;
use Generated\Shared\Checkout\CheckoutResponseInterface;

interface CheckoutClientInterface
{

    /**
     * @param CheckoutRequestInterface $requestInterface
     *
     * @return CheckoutResponseInterface
     */
    public function requestCheckout(CheckoutRequestInterface $requestInterface);

}
