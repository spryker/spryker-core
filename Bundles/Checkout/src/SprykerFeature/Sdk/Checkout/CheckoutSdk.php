<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Sdk\Checkout;

use Generated\Shared\Checkout\CheckoutRequestInterface;
use Guzzle\Http\Message\Response;
use SprykerEngine\Sdk\Kernel\AbstractSdk;

/**
 * @method CheckoutDependencyContainer getDependencyContainer()
 */
class CheckoutSdk extends AbstractSdk
{
    /**
     * @param CheckoutRequestInterface $checkoutRequest
     * @return Response
     */
    public function requestCheckout(CheckoutRequestInterface $checkoutRequest)
    {
        return $this->getDependencyContainer()->createCheckoutManager()->requestCheckout($checkoutRequest);
    }

}
