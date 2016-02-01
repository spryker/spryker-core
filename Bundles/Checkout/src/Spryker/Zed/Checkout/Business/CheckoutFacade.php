<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Checkout\Business;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method CheckoutBusinessFactory getFactory()
 */
class CheckoutFacade extends AbstractFacade
{

    /**
     * @param CheckoutRequestTransfer $checkoutRequest
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function requestCheckout(CheckoutRequestTransfer $checkoutRequest)
    {
        return $this
            ->getFactory()
            ->createCheckoutWorkflow()
            ->requestCheckout($checkoutRequest);
    }

}
