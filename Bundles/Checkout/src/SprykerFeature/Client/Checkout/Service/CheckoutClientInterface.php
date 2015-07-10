<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Checkout\Service;

use Generated\Shared\Checkout\CheckoutRequestInterface;
use SprykerEngine\Shared\Transfer\TransferInterface;

interface CheckoutClientInterface
{

    /**
     * @param CheckoutRequestInterface $requestInterface
     *
     * @return TransferInterface
     */
    public function requestCheckout(CheckoutRequestInterface $requestInterface);

}
