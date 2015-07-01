<?php

namespace SprykerFeature\Client\Checkout\Service;

use Generated\Client\Ide\FactoryAutoCompletion\CheckoutService;
use SprykerEngine\Client\Kernel\Service\AbstractDependencyContainer;
use SprykerFeature\Client\Cart\CartDependencyProvider;
use SprykerFeature\Client\Checkout\Service\Zed\CheckoutStubInterface;

/**
 * @method CheckoutService getFactory()
 */
class CheckoutDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return CheckoutStubInterface
     */
    public function createZedStub()
    {
        $zedStub = $this->getProvidedDependency(CartDependencyProvider::SERVICE_ZED);
        $checkoutStub = $this->getFactory()->createZedCheckoutStub(
            $zedStub
        );

        return $checkoutStub;
    }

}
