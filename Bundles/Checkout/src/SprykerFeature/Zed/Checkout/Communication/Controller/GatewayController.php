<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Checkout\Communication\Controller;

use Generated\Shared\Checkout\CheckoutRequestInterface;
use Generated\Shared\Checkout\CheckoutResponseInterface;
use Generated\Shared\Transfer\CheckoutRequestTransfer;
use SprykerFeature\Zed\Checkout\Business\CheckoutFacade;
use SprykerFeature\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method CheckoutFacade getFacade()
 */
class GatewayController extends AbstractGatewayController
{

    /**
     * @param CheckoutRequestTransfer $checkoutRequestTransfer
     *
     * @return CheckoutResponseInterface
     */
    public function requestCheckoutAction(CheckoutRequestTransfer $checkoutRequestTransfer)
    {
        $result = $this->getFacade()->requestCheckout($checkoutRequestTransfer);

        return $result;
    }

}
