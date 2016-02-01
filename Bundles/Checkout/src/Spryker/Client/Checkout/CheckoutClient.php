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
 * @method CheckoutFactory getFactory()
 */
class CheckoutClient extends AbstractClient implements CheckoutClientInterface
{

    /**
     * @param CheckoutRequestTransfer $checkoutRequest
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function requestCheckout(CheckoutRequestTransfer $checkoutRequest)
    {
        return $this->getZedStub()->requestCheckout($checkoutRequest);
    }

    /**
     * @return \Spryker\Client\Checkout\Zed\CheckoutStub
     */
    protected function getZedStub()
    {
        return $this->getFactory()->createZedStub();
    }

}
