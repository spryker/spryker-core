<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Checkout\Service;

use SprykerFeature\Client\Checkout\CheckoutDependencyProvider;
use SprykerFeature\Client\Checkout\Service\Zed\CheckoutStub;
use Generated\Client\Ide\FactoryAutoCompletion\CheckoutService;
use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;
use SprykerFeature\Client\Checkout\Service\Zed\CheckoutStubInterface;

/**
 * @method CheckoutService getFactory()
 */
class CheckoutDependencyContainer extends AbstractServiceDependencyContainer
{

    /**
     * @return CheckoutStubInterface
     */
    public function createZedStub()
    {
        $zedStub = $this->getProvidedDependency(CheckoutDependencyProvider::SERVICE_ZED);
        $checkoutStub = new CheckoutStub(
            $zedStub
        );

        return $checkoutStub;
    }

}
