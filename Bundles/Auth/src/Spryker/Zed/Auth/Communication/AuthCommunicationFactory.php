<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Auth\Communication;

use Spryker\Zed\Auth\AuthDependencyProvider;
use Spryker\Zed\Auth\Communication\Form\LoginForm;
use Spryker\Zed\Auth\Communication\Form\ResetPasswordForm;
use Spryker\Zed\Auth\Communication\Form\ResetPasswordRequestForm;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Auth\AuthConfig getConfig()
 * @method \Spryker\Zed\Auth\Persistence\AuthQueryContainerInterface getQueryContainer()
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
     * @return \Spryker\Zed\Acl\Dependency\Facade\AclToUserInterface
     */
    public function getUserFacade()
    {
        return $this->getProvidedDependency(AuthDependencyProvider::FACADE_USER);
    }
}
