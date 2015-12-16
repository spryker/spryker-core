<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\User\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\Transfer\UserTransfer;
use Generated\Shared\Transfer\CollectionTransfer;

/**
 * @method UserDependencyContainer getBusinessFactory()
 */
class UserFacade extends AbstractFacade
{

    /**
     * @return void
     */
    public function install()
    {
        $this->getBusinessFactory()
            ->getInstallerModel()
            ->install();
    }

    /**
     * @param string $username
     *
     * @return bool
     */
    public function hasUserByUsername($username)
    {
        return $this->getBusinessFactory()
            ->getUserModel()
            ->hasUserByUsername($username);
    }

    /**
     * @param string $username
     *
     * @return UserTransfer
     */
    public function getUserByUsername($username)
    {
        return $this->getBusinessFactory()
            ->getUserModel()
            ->getUserByUsername($username);
    }

    /**
     * @param int $idUser
     *
     * @return UserTransfer
     */
    public function getUserById($idUser)
    {
        return $this->getBusinessFactory()
            ->getUserModel()
            ->getUserById($idUser);
    }

    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $username
     * @param string $password
     *
     * @return UserTransfer
     */
    public function addUser($firstName, $lastName, $username, $password)
    {
        return $this->getBusinessFactory()
            ->getUserModel()
            ->addUser($firstName, $lastName, $username, $password);
    }

    /**
     * @param UserTransfer $user
     *
     * @throws Exception\UserNotFoundException
     *
     * @return UserTransfer
     */
    public function updateUser(UserTransfer $user)
    {
        return $this->getBusinessFactory()
            ->getUserModel()
            ->save($user);
    }

    /**
     * @param UserTransfer $user
     *
     * @return mixed
     */
    public function setCurrentUser(UserTransfer $user)
    {
        return $this->getBusinessFactory()
            ->getUserModel()
            ->setCurrentUser($user);
    }

    /**
     * @return UserTransfer
     */
    public function getCurrentUser()
    {
        return $this->getBusinessFactory()
            ->getUserModel()
            ->getCurrentUser();
    }

    /**
     * @return bool
     */
    public function hasCurrentUser()
    {
        return $this->getBusinessFactory()
            ->getUserModel()
            ->hasCurrentUser();
    }

    /**
     * @param string $password
     * @param string $hash
     *
     * @return bool
     */
    public function isValidPassword($password, $hash)
    {
        return $this->getBusinessFactory()
            ->getUserModel()
            ->validatePassword($password, $hash);
    }

    /**
     * @param UserTransfer $user
     *
     * @return bool
     */
    public function isSystemUser(UserTransfer $user)
    {
        return $this->getBusinessFactory()
            ->getUserModel()
            ->isSystemUser($user);
    }

    /**
     * @return CollectionTransfer
     */
    public function getSystemUsers()
    {
        return $this->getBusinessFactory()
            ->getUserModel()
            ->getSystemUsers();
    }

    /**
     * @return UserTransfer
     */
    public function getUsers()
    {
        return $this->getBusinessFactory()
            ->getUserModel()
            ->getUsers();
    }

    /**
     * @param int $idUser
     *
     * @return UserTransfer;
     */
    public function removeUser($idUser)
    {
        return $this->getBusinessFactory()
            ->getUserModel()
            ->removeUser($idUser);
    }

    /**
     * @param int $idUser
     *
     * @return bool
     */
    public function activateUser($idUser)
    {
        return $this->getBusinessFactory()->getUserModel()->activateUser($idUser);
    }

    /**
     * @param int $idUser
     *
     * @return bool
     */
    public function deactivateUser($idUser)
    {
        return $this->getBusinessFactory()->getUserModel()->deactivateUser($idUser);
    }

}
