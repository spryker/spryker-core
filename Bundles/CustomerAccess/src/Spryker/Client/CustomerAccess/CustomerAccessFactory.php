<?php

namespace Spryker\Client\CustomerAccess;

use Spryker\Client\CustomerAccess\CustomerAccess\CustomerAccess;
use Spryker\Client\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\CustomerAccess\CustomerAccessConfig getConfig()
 */
class CustomerAccessFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CustomerAccess\CustomerAccess\CustomerAccessInterface
     */
    public function createCustomerAccess()
    {
        return new CustomerAccess($this->getCustomerAccessStorageClient(), $this->getConfig());
    }

    /**
     * @return \Spryker\Client\CustomerAccess\Dependency\Client\CustomerAccessToCustomerClientInterface
     */
    public function getCustomerClient()
    {
        return $this->getProvidedDependency(CustomerAccessDependencyProvider::CLIENT_CUSTOMER);
    }

    public function getCustomerAccessStorageClient()
    {
        return $this->getProvidedDependency(CustomerAccessDependencyProvider::CLIENT_CUSTOMER_ACCESS_STORAGE);
    }
}
