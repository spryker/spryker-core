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
use Spryker\Zed\Auth\AuthConfig;
use Spryker\Zed\Auth\Persistence\AuthQueryContainer;

/**
 * @method AuthConfig getConfig()
 * @method AuthQueryContainer getQueryContainer()
 */
class AuthCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\Auth\Communication\Form\LoginForm
     */
    public function createLoginForm()
    {
        $form = new LoginForm();

        return $this->createForm($form);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createResetPasswordRequestForm()
    {
        $form = new ResetPasswordRequestForm();

        return $this->createForm($form);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createResetPasswordForm()
    {
        $form = new ResetPasswordForm();

        return $this->createForm($form);
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
