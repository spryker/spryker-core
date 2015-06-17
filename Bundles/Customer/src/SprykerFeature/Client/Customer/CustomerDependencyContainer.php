<?php

namespace SprykerFeature\Client\Customer;

use SprykerEngine\Client\Kernel\AbstractDependencyContainer;
use SprykerFeature\Client\Customer\Model\Customer;
use Generated\Yves\Ide\FactoryAutoCompletion\Customer as CustomerFactory;
use SprykerFeature\Client\ZedRequest\Provider\ZedClientProvider;

/**
 * @method CustomerFactory getFactory()
 */
class CustomerDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return ZedClientProvider
     */
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
