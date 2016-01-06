<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\User\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\User\Business\UserFacade;
use Spryker\Zed\User\Communication\Form\UserForm;
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
        $form = new ResetPasswordForm($userFacade);

        return $this->createForm($form);
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
     * @return UserForm
     */
    public function createUserForm()
    {
        $form = new UserForm($this->createAclFacade());

        return $this->createForm($form);
    }

    /**
     * @param int $idUser
     * @param UserFacade $userFacade
     *
     * @return UserUpdateForm
     */
    public function createUpdateUserForm($idUser, UserFacade $userFacade)
    {
        $form = new UserUpdateForm($idUser, $userFacade, $this->createAclFacade());

        return $this->createForm($form);
    }

    /**
     * @return UserToAclInterface
     */
    public function createAclFacade()
    {
        return $this->getProvidedDependency(UserDependencyProvider::FACADE_ACL);
    }

}
