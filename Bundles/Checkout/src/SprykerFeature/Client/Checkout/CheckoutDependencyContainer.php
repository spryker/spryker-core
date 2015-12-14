<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Checkout;

use SprykerFeature\Client\Checkout\CheckoutDependencyProvider;
use SprykerFeature\Client\Checkout\Zed\CheckoutStub;
use SprykerEngine\Client\Kernel\AbstractDependencyContainer;
use SprykerFeature\Client\Checkout\Zed\CheckoutStubInterface;

class CheckoutDependencyContainer extends AbstractDependencyContainer
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
