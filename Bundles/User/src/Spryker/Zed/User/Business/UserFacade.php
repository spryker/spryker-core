<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\User\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\Transfer\UserTransfer;
use Generated\Shared\Transfer\CollectionTransfer;

/**
 * @method UserBusinessFactory getFactory()
 */
class UserFacade extends AbstractFacade
{

    /**
     * @return void
     */
    public function install()
    {
        $this->getFactory()
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
        return $this->getFactory()
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
        return $this->getFactory()
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
        return $this->getFactory()
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
        return $this->getFactory()
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
        return $this->getFactory()
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
        return $this->getFactory()
            ->getUserModel()
            ->setCurrentUser($user);
    }

    /**
     * @return UserTransfer
     */
    public function getCurrentUser()
    {
        return $this->getFactory()
            ->getUserModel()
            ->getCurrentUser();
    }

    /**
     * @return bool
     */
    public function hasCurrentUser()
    {
        return $this->getFactory()
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
        return $this->getFactory()
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
        return $this->getFactory()
            ->getUserModel()
            ->isSystemUser($user);
    }

    /**
     * @return CollectionTransfer
     */
    public function getSystemUsers()
    {
        return $this->getFactory()
            ->getUserModel()
            ->getSystemUsers();
    }

    /**
     * @return UserTransfer
     */
    public function getUsers()
    {
        return $this->getFactory()
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
        return $this->getFactory()
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
        return $this->getFactory()->getUserModel()->activateUser($idUser);
    }

    /**
     * @param int $idUser
     *
     * @return bool
     */
    public function deactivateUser($idUser)
    {
        return $this->getFactory()->getUserModel()->deactivateUser($idUser);
    }

}
