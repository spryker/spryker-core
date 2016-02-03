<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\User\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\User\Business\UserFacade;
use Spryker\Zed\User\Communication\Form\DataProvider\UserFormDataProvider;
use Spryker\Zed\User\Communication\Form\DataProvider\UserUpdateFormDataProvider;
use Spryker\Zed\User\Communication\Form\UserForm;
use Spryker\Zed\User\Communication\Form\UserUpdateForm;
use Spryker\Zed\User\Communication\Table\UsersTable;
use Spryker\Zed\User\Communication\Form\ResetPasswordForm;
use Spryker\Zed\User\UserDependencyProvider;

/**
 * @method \Spryker\Zed\User\Persistence\UserQueryContainer getQueryContainer()
 * @method \Spryker\Zed\User\UserConfig getConfig()
 * @method \Spryker\Zed\User\Business\UserFacade getFacade()
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
        $formType = new ResetPasswordForm($userFacade);

        return $this->getFormFactory()->create($formType);
    }

    /**
     * @return \Spryker\Zed\User\Communication\Table\UsersTable
     */
    public function createUserTable()
    {
        return new UsersTable(
            $this->getQueryContainer()
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
        $formType = new UserForm();

        return $this->getFormFactory()->create($formType, $data, $options);
    }

    /**
     * @param array $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createUpdateUserForm(array $data = [], array $options = [])
    {
        $formType = new UserUpdateForm();

        return $this->getFormFactory()->create($formType, $data, $options);
    }

    /**
     * @return \Spryker\Zed\User\Communication\Form\DataProvider\UserFormDataProvider
     */
    public function createUserFormDataProvider()
    {
        return new UserFormDataProvider($this->getAclFacade(), $this->getFacade());
    }

    /**
     * @return \Spryker\Zed\User\Communication\Form\DataProvider\UserUpdateFormDataProvider
     */
    public function createUserUpdateFormDataProvider()
    {
        return new UserUpdateFormDataProvider($this->getAclFacade(), $this->getFacade());
    }

    /**
     * @return \Spryker\Zed\User\Dependency\Facade\UserToAclInterface
     */
    public function getAclFacade()
    {
        return $this->getProvidedDependency(UserDependencyProvider::FACADE_ACL);
    }

    /**
     * @deprecated Use getAclFacade() instead.
     *
     * @return \Spryker\Zed\User\Dependency\Facade\UserToAclInterface
     */
    public function createAclFacade()
    {
        trigger_error('Deprecated, use getAclFacade() instead.', E_USER_DEPRECATED);

        return $this->getAclFacade();
    }

}
