<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Checkout\Service;

use Generated\Shared\Checkout\CheckoutRequestInterface;
use Generated\Shared\Checkout\CheckoutResponseInterface;
use SprykerEngine\Client\Kernel\Service\AbstractClient;
use SprykerFeature\Client\Checkout\Service\Zed\CheckoutStub;

/**
 * @method CheckoutDependencyContainer getDependencyContainer()
 */
class CheckoutClient extends AbstractClient implements CheckoutClientInterface
{

    /**
     * @param CheckoutRequestInterface $checkoutRequest
     *
     * @return CheckoutResponseInterface
     */
    public function requestCheckout(CheckoutRequestInterface $checkoutRequest)
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
