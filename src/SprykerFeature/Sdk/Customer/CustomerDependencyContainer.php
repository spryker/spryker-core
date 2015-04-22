<?php

namespace SprykerFeature\Sdk\Customer;

use SprykerEngine\Sdk\Kernel\AbstractDependencyContainer;
use SprykerFeature\Sdk\Customer\Model\Customer;
use Generated\Yves\Ide\FactoryAutoCompletion\Customer as CustomerFactory;

/**
 * @method CustomerFactory getFactory()
 */
class CustomerDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return Customer
     */
    public function createModelCustomer()
    {
        return $this->getFactory()->createModelCustomer($this->getFactory(), $this->getLocator());
    }
}
