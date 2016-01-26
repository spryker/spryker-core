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
use Symfony\Component\Form\FormInterface;

/**
 * @method UserQueryContainer getQueryContainer()
 * @method UserConfig getConfig()
 */
class UserCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @param UserFacade $userFacade
     *
     * @return FormInterface
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
     * @return FormInterface
     */
    public function createUserForm()
    {
        $form = new UserForm($this->getAclFacade());

        return $this->createForm($form);
    }

    /**
     * @param int $idUser
     * @param UserFacade $userFacade
     *
     * @return FormInterface
     */
    public function createUpdateUserForm($idUser, UserFacade $userFacade)
    {
        $form = new UserUpdateForm($idUser, $userFacade, $this->getAclFacade());

        return $this->createForm($form);
    }

    /**
     * @return UserToAclInterface
     */
    public function getAclFacade()
    {
        return $this->getProvidedDependency(UserDependencyProvider::FACADE_ACL);
    }

    /**
     * @deprecated Use getAclFacade() instead.
     *
     * @return UserToAclInterface
     */
    public function createAclFacade()
    {
        trigger_error('Deprecated, use getAclFacade() instead.', E_USER_DEPRECATED);

        return $this->getAclFacade();
    }

}
