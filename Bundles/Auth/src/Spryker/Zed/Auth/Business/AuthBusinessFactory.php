<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Auth\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Auth\AuthConfig;
use Spryker\Zed\Auth\Business\Client\StaticToken;
use Spryker\Zed\Auth\Business\Model\Auth;
use Spryker\Zed\Auth\Business\Model\PasswordReset;
use Spryker\Zed\Auth\AuthDependencyProvider;
use Spryker\Zed\Auth\Persistence\AuthQueryContainer;

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
        //@todo refactor those messy dependencies.
        return new Auth(
            $this->getLocator(),
            $this->getLocator()->session()->client(),
            $this->getLocator()->user()->facade(),
            $this->getConfig(),
            $this->createStaticTokenClient()
        );
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
            $this->getProvidedDependency(AuthDependencyProvider::FACADE_USER),
            $this->getConfig()
        );

        $passwordResetNotificationSender = $this->getProvidedDependency(AuthDependencyProvider::PASSWORD_RESET_SENDER);
        if ($passwordResetNotificationSender !== null) {
            $passwordReset->setUserPasswordResetNotificationSender($passwordResetNotificationSender);
        }

        return $passwordReset;
    }

}
