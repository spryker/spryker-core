<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Checkout\Service\Zed;

use Generated\Shared\Checkout\CheckoutRequestInterface;
use SprykerEngine\Shared\Transfer\TransferInterface;

interface CheckoutStubInterface
{

    /**
     * @param CheckoutRequestInterface $transferCheckout
     *
     * @return TransferInterface
     */
    public function requestCheckout(CheckoutRequestInterface $transferCheckout);

}
