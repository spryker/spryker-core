<?php

namespace Spryker\Zed\CustomerAccessStorage\Business;

use Spryker\Zed\CustomerAccessStorage\Business\Model\CustomerAccessStorage;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CustomerAccessStorage\Persistence\CustomerAccessStorageQueryContainerInterface getQueryContainer()
 */
class CustomerAccessStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     *
     * @return \Spryker\Zed\CustomerAccessStorage\Business\Model\CustomerAccessStorageInterface
     */
    public function createCustomerAccessStorage()
    {
        return new CustomerAccessStorage($this->getQueryContainer());
    }
}
