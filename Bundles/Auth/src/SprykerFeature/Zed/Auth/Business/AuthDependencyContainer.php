<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Auth\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\AuthBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Auth\AuthConfig;
use SprykerFeature\Zed\Auth\Business\Client\StaticToken;
use SprykerFeature\Zed\Auth\Business\Model\Auth;
use SprykerFeature\Zed\Auth\Business\Model\PasswordReset;
use SprykerFeature\Zed\Auth\AuthDependencyProvider;
use SprykerFeature\Zed\Auth\Persistence\AuthQueryContainer;

/**
 * @method AuthBusiness getFactory()
 * @method AuthConfig getConfig()
 * @method AuthQueryContainer getQueryContainer()
 */
class AuthDependencyContainer extends AbstractBusinessDependencyContainer
{
    /**
     * @return Auth
     */
    public function createAuthModel()
    {
        //@todo refactor those messy dependencies.
        return $this->getFactory()->createModelAuth(
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
        return $this->getFactory()->createClientStaticToken();
    }

    /**
     * @return PasswordReset
     */
    public function createPasswordReset()
    {
        $passwordReset =  $this->getFactory()->createModelPasswordReset(
            $this->getQueryContainer(),
            $this->getProvidedDependency(AuthDependencyProvider::FACADE_USER),
            $this->getConfig()
        );

        $passwordResetSender = $this->getProvidedDependency(AuthDependencyProvider::PASSWORD_RESET_SENDER);
        if (null !== $passwordResetSender) {
            $passwordReset->setUserPasswordResetSender($passwordResetSender);
        }

        return $passwordReset;
    }

}
