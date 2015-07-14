<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Customer\Communication;

use SprykerEngine\Yves\Kernel\AbstractDependencyContainer;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use SprykerFeature\Yves\Customer\Communication\Provider\SecurityServiceProvider;
use SprykerFeature\Yves\Customer\Communication\Provider\UserProvider;
use Silex\Application;
use SprykerFeature\Yves\Customer\Communication\Model\Customer;

/**
 * @method \Generated\Yves\Ide\FactoryAutoCompletion\Customer getFactory()
 */
class CustomerDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return SecurityServiceProvider
     */
    public function createSecurityServiceProvider()
    {
        return $this->getFactory()->createProviderSecurityServiceProvider($this->getFactory(), $this->getLocator());
    }

    /**
     * @param SessionInterface $session
     *
     * @return UserProvider
     */
    public function createUserProvider(SessionInterface $session)
    {
        return $this->getFactory()->createProviderUserProvider($this->getFactory(), $this->getLocator(), $session);
    }

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
