<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Auth\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\AuthCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Auth\Communication\Form\LoginForm;
use SprykerFeature\Zed\Auth\Communication\Form\ResetPasswordRequestForm;
use SprykerFeature\Zed\User\Business\UserFacade;
use SprykerFeature\Zed\User\Communication\Form\ResetPasswordForm;
use SprykerFeature\Zed\Auth\AuthDependencyProvider;
use SprykerFeature\Zed\Auth\AuthConfig;

/**
 * @method AuthCommunication getFactory()
 * @method AuthConfig getConfig()
 */
class AuthDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return LoginForm
     */
    public function createLoginForm()
    {
        return $this->getFactory()->createFormLoginForm();
    }

    /**
     * @return ResetPasswordRequestForm
     */
    public function createResetPasswordRequestForm()
    {
        return $this->getFactory()->createFormResetPasswordRequestForm();
    }

    /**
     * @return ResetPasswordForm
     */
    public function createResetPasswordForm()
    {
        return $this->getFactory()->createFormResetPasswordForm();
    }

    /**
     * @return UserFacade
     */
    public function createUserFacade()
    {
        return $this->getProvidedDependency(AuthDependencyProvider::FACADE_USER);
    }

}
