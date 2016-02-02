<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Checkout\Communication\Controller;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Spryker\Zed\Checkout\Business\CheckoutFacade;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method CheckoutFacade getFacade()
 */
class GatewayController extends AbstractGatewayController
{

    /**
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $checkoutRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function requestCheckoutAction(CheckoutRequestTransfer $checkoutRequestTransfer)
    {
        $result = $this->getFacade()->requestCheckout($checkoutRequestTransfer);

        return $result;
    }

}
