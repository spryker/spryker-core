<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Auth\Business;

use Spryker\Zed\Auth\AuthDependencyProvider;
use Spryker\Zed\Auth\Business\Client\StaticToken;
use Spryker\Zed\Auth\Business\Model\Auth;
use Spryker\Zed\Auth\Business\Model\PasswordReset;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Auth\AuthConfig getConfig()
 * @method \Spryker\Zed\Auth\Persistence\AuthQueryContainer getQueryContainer()
 */
class AuthBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Auth\Business\Model\Auth
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
     * @return \Spryker\Client\Session\SessionClientInterface
     */
    protected function getSessionClient()
    {
        return $this->getProvidedDependency(AuthDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return \Spryker\Zed\Auth\Dependency\Facade\AuthToUserInterface
     */
    protected function getUserFacade()
    {
        return $this->getProvidedDependency(AuthDependencyProvider::FACADE_USER);
    }

    /**
     * @return \Spryker\Zed\Auth\Business\Client\StaticToken
     */
    public function createStaticTokenClient()
    {
        return new StaticToken();
    }

    /**
     * @return \Spryker\Zed\Auth\Business\Model\PasswordReset
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
