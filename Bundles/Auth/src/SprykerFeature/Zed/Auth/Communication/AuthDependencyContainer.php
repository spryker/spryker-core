<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Auth\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Auth\Communication\Form\LoginForm;
use SprykerFeature\Zed\Auth\Communication\Form\ResetPasswordForm;
use SprykerFeature\Zed\Auth\Communication\Form\ResetPasswordRequestForm;
use SprykerFeature\Zed\User\Business\UserFacade;
use SprykerFeature\Zed\Auth\AuthDependencyProvider;
use SprykerFeature\Zed\Auth\AuthConfig;

/**
 * @method AuthConfig getConfig()
 */
class AuthDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return LoginForm
     */
    public function createLoginForm()
    {
        return new LoginForm();
    }

    /**
     * @return ResetPasswordRequestForm
     */
    public function createResetPasswordRequestForm()
    {
        return new ResetPasswordRequestForm();
    }

    /**
     * @return ResetPasswordForm
     */
    public function createResetPasswordForm()
    {
        return new ResetPasswordForm();
    }

    /**
     * @return UserFacade
     */
    public function createUserFacade()
    {
        return $this->getProvidedDependency(AuthDependencyProvider::FACADE_USER);
    }

}
