<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\User\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\User\Business\UserFacade;
use Spryker\Zed\User\Communication\Form\UserCreateForm;
use Spryker\Zed\User\Communication\Form\UserUpdateForm;
use Spryker\Zed\User\Communication\Table\UsersTable;
use Spryker\Zed\User\Communication\Form\ResetPasswordForm;
use Spryker\Zed\User\Dependency\Facade\UserToAclInterface;
use Spryker\Zed\User\Persistence\UserQueryContainer;
use Spryker\Zed\User\UserDependencyProvider;
use Spryker\Zed\User\UserConfig;

/**
 * @method UserQueryContainer getQueryContainer()
 * @method UserConfig getConfig()
 */
class UserCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @param UserFacade $userFacade
     *
     * @return ResetPasswordForm
     */
    public function createResetPasswordForm(UserFacade $userFacade)
    {
        return new ResetPasswordForm($userFacade);
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
        return new UserCreateForm($this->createAclFacade());
    }

    /**
     * @param int $idUser
     * @param UserFacade $userFacade
     *
     * @return UserUpdateForm
     */
    public function createUpdateUserForm($idUser, UserFacade $userFacade)
    {
        return new UserUpdateForm($idUser, $userFacade, $this->createAclFacade());
    }

    /**
     * @return UserToAclInterface
     */
    public function createAclFacade()
    {
        return $this->getProvidedDependency(UserDependencyProvider::FACADE_ACL);
    }

}
