<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Checkout;

use Spryker\Client\Checkout\Zed\CheckoutStub;
use Spryker\Client\Kernel\AbstractFactory;

class CheckoutFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\Checkout\Zed\CheckoutStubInterface
     */
    public function createZedStub()
    {
        return new CheckoutStub($this->getProvidedDependency(CheckoutDependencyProvider::SERVICE_ZED));
    }

}
