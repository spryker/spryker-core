<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Checkout;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;
use Spryker\Client\Checkout\Zed\CheckoutStub;

/**
 * @method CheckoutDependencyContainer getFactory()
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
        return $this->getFactory()->createZedStub();
    }

}
