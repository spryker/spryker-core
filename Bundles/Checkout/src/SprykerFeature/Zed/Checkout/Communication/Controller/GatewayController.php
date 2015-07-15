<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Checkout\Communication\Controller;

use Generated\Shared\Checkout\CheckoutRequestInterface;
use Generated\Shared\Checkout\CheckoutResponseInterface;
use SprykerFeature\Zed\Checkout\Business\CheckoutFacade;
use SprykerFeature\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method CheckoutFacade getFacade()
 */
class GatewayController extends AbstractGatewayController
{

    /**
     * @param CheckoutRequestInterface $checkoutRequest
     *
     * @return CheckoutResponseInterface
     */
    public function requestCheckoutAction(CheckoutRequestInterface $checkoutRequest)
    {
        $result = $this->getFacade()->requestCheckout($checkoutRequest);

        return $result;
    }

}
