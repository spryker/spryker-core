<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Customer\Plugin;

use SprykerEngine\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use SprykerFeature\Yves\Customer\Provider\UserProvider;
use SprykerFeature\Yves\Customer\Provider\SecurityServiceProvider;
use SprykerFeature\Yves\Customer\CustomerDependencyContainer;

/**
 * @method CustomerDependencyContainer getDependencyContainer()
 */
class SecurityServicePlugin extends AbstractPlugin
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
