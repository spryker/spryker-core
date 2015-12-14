<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Customer;

use SprykerFeature\Client\Customer\Session\CustomerSession;
use SprykerFeature\Client\Customer\Zed\CustomerStub;
use SprykerEngine\Client\Kernel\AbstractDependencyContainer;
use SprykerFeature\Client\Customer\CustomerDependencyProvider;
use SprykerFeature\Client\Customer\Session\CustomerSessionInterface;
use SprykerFeature\Client\Customer\Zed\CustomerStubInterface;

class CustomerDependencyContainer extends AbstractDependencyContainer
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
