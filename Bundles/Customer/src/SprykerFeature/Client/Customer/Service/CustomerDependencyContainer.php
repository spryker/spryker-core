<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Customer\Service;

use Generated\Client\Ide\FactoryAutoCompletion\CustomerService;
use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;
use SprykerFeature\Client\Customer\CustomerDependencyProvider;
use SprykerFeature\Client\Customer\Service\Session\CustomerSessionInterface;
use SprykerFeature\Client\Customer\Service\Symfony\CustomerSecurityInterface;
use SprykerFeature\Client\Customer\Service\Zed\CustomerStubInterface;

/**
 * @method CustomerService getFactory()
 */
class CustomerDependencyContainer extends AbstractServiceDependencyContainer
{

    /**
     * @return CustomerStubInterface
     */
    public function createZedCustomerStub()
    {
        return $this->getFactory()->createZedCustomerStub(
            $this->getProvidedDependency(CustomerDependencyProvider::SERVICE_ZED)
        );
    }

    /**
     * @return CustomerSessionInterface
     */
    public function createSessionCustomerSession()
    {
        return $this->getFactory()->createSessionCustomerSession(
            $this->getProvidedDependency(CustomerDependencyProvider::SERVICE_SESSION)
        );
    }

}
