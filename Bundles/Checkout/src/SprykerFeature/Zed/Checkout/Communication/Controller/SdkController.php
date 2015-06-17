<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Checkout\Communication\Controller;

use Generated\Shared\Checkout\CheckoutRequestInterface;
use Generated\Shared\Checkout\CheckoutResponseInterface;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractSdkController;
use SprykerFeature\Zed\Checkout\Business\CheckoutFacade;

/**
 * @method CheckoutFacade getFacade()
 */
class ClientController extends AbstractClientController
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
