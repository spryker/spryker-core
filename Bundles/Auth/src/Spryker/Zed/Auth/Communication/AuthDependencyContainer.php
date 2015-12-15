<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Auth\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use Spryker\Zed\Auth\Communication\Form\LoginForm;
use Spryker\Zed\Auth\Communication\Form\ResetPasswordForm;
use Spryker\Zed\Auth\Communication\Form\ResetPasswordRequestForm;
use Spryker\Zed\User\Business\UserFacade;
use Spryker\Zed\Auth\AuthDependencyProvider;
use Spryker\Zed\Auth\AuthConfig;

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
