<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Auth\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Auth\Communication\Form\LoginForm;
use Spryker\Zed\Auth\Communication\Form\ResetPasswordForm;
use Spryker\Zed\Auth\Communication\Form\ResetPasswordRequestForm;
use Spryker\Zed\Auth\AuthDependencyProvider;

/**
 * @method \Spryker\Zed\Auth\AuthConfig getConfig()
 * @method \Spryker\Zed\Auth\Persistence\AuthQueryContainer getQueryContainer()
 */
class AuthCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createLoginForm()
    {
        $formType = new LoginForm();

        return $this->getFormFactory()->create($formType);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createResetPasswordRequestForm()
    {
        $formType = new ResetPasswordRequestForm();

        return $this->getFormFactory()->create($formType);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createResetPasswordForm()
    {
        $formType = new ResetPasswordForm();

        return $this->getFormFactory()->create($formType);
    }

    /**
     * @deprecated Use getUserFacade() instead.
     *
     * @return \Spryker\Zed\User\Business\UserFacade
     */
    public function createUserFacade()
    {
        trigger_error('Deprecated, use getUserFacade() instead.', E_USER_DEPRECATED);

        return $this->getUserFacade();
    }

    /**
     * @return \Spryker\Zed\User\Business\UserFacade
     */
    public function getUserFacade()
    {
        return $this->getProvidedDependency(AuthDependencyProvider::FACADE_USER);
    }

}
