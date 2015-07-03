<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Customer\Service;

use SprykerEngine\Client\Kernel\Service\AbstractDependencyContainer;
use SprykerFeature\Client\Customer\Service\Model\Customer;
use Generated\Yves\Ide\FactoryAutoCompletion\Customer as CustomerFactory;
use SprykerFeature\Client\ZedRequest\Service\Provider\ZedClientProvider;

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
