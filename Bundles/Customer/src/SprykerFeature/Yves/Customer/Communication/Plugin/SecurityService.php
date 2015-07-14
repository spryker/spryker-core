<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Customer\Communication\Plugin;

use SprykerEngine\Yves\Kernel\Communication\AbstractPlugin;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use SprykerFeature\Yves\Customer\Communication\Provider\UserProvider;
use SprykerFeature\Yves\Customer\Communication\Provider\SecurityServiceProvider;
use SprykerFeature\Yves\Customer\Communication\CustomerDependencyContainer;

/**
 * @method CustomerDependencyContainer getDependencyContainer()
 */
class SecurityService extends AbstractPlugin
{

    /**
     * @return SecurityServiceProvider
     */
    public function createSecurityServiceProvider()
    {
        return $this->getDependencyContainer()->createSecurityServiceProvider();
    }

    /**
     * @param SessionInterface $session
     *
     * @return UserProvider
     */
    public function createUserProvider(SessionInterface $session)
    {
        return $this->getDependencyContainer()->createUserProvider($session);
    }

}
