<?php

/**
* (c) Spryker Systems GmbH copyright protected
*/

namespace SprykerFeature\Zed\Checkout\Business;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method CheckoutDependencyContainer getDependencyContainer()
 */
class CheckoutFacade extends AbstractFacade
{

    /**
     * @param CheckoutRequestTransfer $checkoutRequest
     *
     * @return CheckoutResponseTransfer
     */
    public function requestCheckout(CheckoutRequestTransfer $checkoutRequest)
    {
        return $this
            ->getDependencyContainer()
            ->createCheckoutWorkflow()
            ->requestCheckout($checkoutRequest)
            ;
    }

}
