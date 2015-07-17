<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Customer\Communication;

use Generated\Yves\Ide\FactoryAutoCompletion\CustomerCommunication;
use SprykerEngine\Yves\Kernel\Communication\AbstractCommunicationDependencyContainer;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Silex\Application;
use SprykerFeature\Yves\Customer\Communication\Model\Customer;

/**
 * @method CustomerCommunication getFactory()
 */
class CustomerDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @param Application $application
     *
     * @return Customer
     */
    public function createCustomer(Application $application)
    {
        return $this->getFactory()->createModelCustomer($application);
    }

}
