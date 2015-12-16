<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Customer;

use Spryker\Client\Customer\Session\CustomerSession;
use Spryker\Client\Customer\Zed\CustomerStub;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Customer\CustomerDependencyProvider;
use Spryker\Client\Customer\Session\CustomerSessionInterface;
use Spryker\Client\Customer\Zed\CustomerStubInterface;

class CustomerDependencyContainer extends AbstractFactory
{

    /**
     * @return CustomerStubInterface
     */
    public function createZedCustomerStub()
    {
        return new CustomerStub(
            $this->getProvidedDependency(CustomerDependencyProvider::SERVICE_ZED)
        );
    }

    /**
     * @return CustomerSessionInterface
     */
    public function createSessionCustomerSession()
    {
        return new CustomerSession(
            $this->getProvidedDependency(CustomerDependencyProvider::SERVICE_SESSION)
        );
    }

}
