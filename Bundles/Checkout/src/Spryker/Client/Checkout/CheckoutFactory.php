<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Checkout;

use Spryker\Client\Checkout\CheckoutDependencyProvider;
use Spryker\Client\Checkout\Zed\CheckoutStub;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Checkout\Zed\CheckoutStubInterface;

class CheckoutFactory extends AbstractFactory
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
