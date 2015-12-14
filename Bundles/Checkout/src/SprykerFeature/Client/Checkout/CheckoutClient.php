<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Checkout;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use SprykerEngine\Client\Kernel\AbstractClient;
use SprykerFeature\Client\Checkout\Zed\CheckoutStub;

/**
 * @method CheckoutDependencyContainer getDependencyContainer()
 */
class CheckoutClient extends AbstractClient implements CheckoutClientInterface
{

    /**
     * @param CheckoutRequestTransfer $checkoutRequest
     *
     * @return CheckoutResponseTransfer
     */
    public function requestCheckout(CheckoutRequestTransfer $checkoutRequest)
    {
        return $this->getZedStub()->requestCheckout($checkoutRequest);
    }

    /**
     * @return CheckoutStub
     */
    protected function getZedStub()
    {
        return $this->getDependencyContainer()->createZedStub();
    }

}
