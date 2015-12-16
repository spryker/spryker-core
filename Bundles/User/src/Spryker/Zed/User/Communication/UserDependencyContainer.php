<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\User\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Acl\Business\AclFacade;
use Spryker\Zed\User\Communication\Form\UserCreateForm;
use Spryker\Zed\User\Communication\Form\UserUpdateForm;
use Spryker\Zed\User\Communication\Table\UsersTable;
use Spryker\Zed\User\Communication\Form\ResetPasswordForm;
use Spryker\Zed\User\Persistence\UserQueryContainer;
use Spryker\Zed\User\UserDependencyProvider;

/**
 * @method UserQueryContainer getQueryContainer()
 */
class UserDependencyContainer extends AbstractCommunicationFactory
{

    /**
     * @return ResetPasswordForm
     */
    public function createResetPasswordForm()
    {
        return new ResetPasswordForm();
    }

    /**
     * @return UsersTable
     */
    public function createUserTable()
    {
        return new UsersTable(
            $this->getQueryContainer()
        );
    }

    /**
     * @return UserCreateForm
     */
    public function createUserForm()
    {
        return new UserCreateForm();
    }

    /**
     * @param int $idUser
     *
     * @return UserUpdateForm
     */
    public function createUpdateUserForm($idUser)
    {
        return new UserUpdateForm($idUser);
    }

    /**
     * @return AclFacade
     */
    public function createAclFacade()
    {
        return $this->getProvidedDependency(UserDependencyProvider::FACADE_ACL);
    }

}
