<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Auth\Business;

use Spryker\Client\Session\SessionClientInterface;
use Spryker\Zed\Auth\Dependency\Facade\AuthToUserBridge;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Auth\AuthConfig;
use Spryker\Zed\Auth\Business\Client\StaticToken;
use Spryker\Zed\Auth\Business\Model\Auth;
use Spryker\Zed\Auth\Business\Model\PasswordReset;
use Spryker\Zed\Auth\AuthDependencyProvider;
use Spryker\Zed\Auth\Persistence\AuthQueryContainer;
use Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException;

/**
 * @method AuthConfig getConfig()
 * @method AuthQueryContainer getQueryContainer()
 */
class AuthBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return Auth
     */
    public function createAuthModel()
    {
        return new Auth(
            $this->getSessionClient(),
            $this->getUserFacade(),
            $this->getConfig(),
            $this->createStaticTokenClient()
        );
    }

    /**
     * @throws ContainerKeyNotFoundException
     *
     * @return SessionClientInterface
     */
    protected function getSessionClient()
    {
        return $this->getProvidedDependency(AuthDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @throws ContainerKeyNotFoundException
     *
     * @return AuthToUserBridge
     */
    protected function getUserFacade()
    {
        return $this->getProvidedDependency(AuthDependencyProvider::FACADE_USER);
    }

    /**
     * @return StaticToken
     */
    public function createStaticTokenClient()
    {
        return new StaticToken();
    }

    /**
     * @return PasswordReset
     */
    public function createPasswordReset()
    {
        $passwordReset = new PasswordReset(
            $this->getQueryContainer(),
            $this->getUserFacade(),
            $this->getConfig()
        );

        $passwordResetNotificationSender = $this->getProvidedDependency(AuthDependencyProvider::PASSWORD_RESET_SENDER);
        if ($passwordResetNotificationSender !== null) {
            $passwordReset->setUserPasswordResetNotificationSender($passwordResetNotificationSender);
        }

        return $passwordReset;
    }

}
