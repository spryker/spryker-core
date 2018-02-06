<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\User\Business\UserFacade;
use Spryker\Zed\User\Communication\Form\DataProvider\UserFormDataProvider;
use Spryker\Zed\User\Communication\Form\DataProvider\UserUpdateFormDataProvider;
use Spryker\Zed\User\Communication\Form\ResetPasswordForm;
use Spryker\Zed\User\Communication\Form\UserForm;
use Spryker\Zed\User\Communication\Form\UserUpdateForm;
use Spryker\Zed\User\Communication\Table\UsersTable;
use Spryker\Zed\User\UserDependencyProvider;

/**
 * @method \Spryker\Zed\User\Persistence\UserQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\User\UserConfig getConfig()
 * @method \Spryker\Zed\User\Business\UserFacadeInterface getFacade()
 */
class UserCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @param \Spryker\Zed\User\Business\UserFacade $userFacade
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createResetPasswordForm(UserFacade $userFacade)
    {
        return $this->getFormFactory()->create(ResetPasswordForm::class);
    }

    /**
     * @return \Spryker\Zed\User\Communication\Table\UsersTable
     */
    public function createUserTable()
    {
        return new UsersTable(
            $this->getQueryContainer(),
            $this->getProvidedDependency(UserDependencyProvider::SERVICE_DATE_FORMATTER),
            $this->getUsersTableExtenderPlugins()
        );
    }

    /**
     * @param array $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createUserForm(array $data = [], array $options = [])
    {
        return $this->getFormFactory()->create(UserForm::class, $data, $options);
    }

    /**
     * @param array $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createUpdateUserForm(array $data = [], array $options = [])
    {
        return $this->getFormFactory()->create(UserUpdateForm::class, $data, $options);
    }

    /**
     * @return \Spryker\Zed\User\Communication\Form\DataProvider\UserFormDataProvider
     */
    public function createUserFormDataProvider()
    {
        return new UserFormDataProvider($this->getGroupPlugin(), $this->getFacade());
    }

    /**
     * @return \Spryker\Zed\User\Communication\Form\DataProvider\UserUpdateFormDataProvider
     */
    public function createUserUpdateFormDataProvider()
    {
        return new UserUpdateFormDataProvider($this->getGroupPlugin(), $this->getFacade());
    }

    /**
     * @return \Spryker\Zed\User\Dependency\Plugin\GroupPluginInterface
     */
    public function getGroupPlugin()
    {
        return $this->getProvidedDependency(UserDependencyProvider::PLUGIN_GROUP);
    }

    /**
     * @return \Spryker\Zed\User\Dependency\Plugin\UsersTableExpanderPluginInterface[]
     */
    protected function getUsersTableExtenderPlugins()
    {
        return $this->getProvidedDependency(UserDependencyProvider::PLUGINS_USERS_TABLE_EXTENDER);
    }
}
