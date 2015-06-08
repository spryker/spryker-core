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
    protected function createZedClient()
    {
        return $this->getLocator()->zedRequest()->zedClient();
    }

    /**
     * @return Customer
     */
    public function createModelCustomer()
    {
        return $this->getFactory()->createModelCustomer($this->createZedClient());
    }
}
