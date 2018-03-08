<?php

namespace Spryker\Zed\CustomerAccessGui\Communication;

use Spryker\Zed\CustomerAccessGui\CustomerAccessGuiDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class CustomerAccessGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CustomerAccessGui\Dependency\Facade\CustomerAccessGuiToCustomerAccessFacadeInterface
     */
    public function getCustomerAccessFacade()
    {
        return $this->getProvidedDependency(CustomerAccessGuiDependencyProvider::FACADE_CUSTOMER_ACCESS);
    }
}