<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Customer\Service;

use SprykerFeature\Client\Customer\Service\Session\CustomerSession;
use SprykerFeature\Client\Customer\Service\Zed\CustomerStub;
use Generated\Client\Ide\FactoryAutoCompletion\CustomerService;
use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;
use SprykerFeature\Client\Customer\CustomerDependencyProvider;
use SprykerFeature\Client\Customer\Service\Session\CustomerSessionInterface;
use SprykerFeature\Client\Customer\Service\Zed\CustomerStubInterface;

class CustomerDependencyContainer extends AbstractServiceDependencyContainer
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
