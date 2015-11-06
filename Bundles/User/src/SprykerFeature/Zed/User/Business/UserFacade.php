<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\User\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\Transfer\UserTransfer;
use Generated\Shared\Transfer\CollectionTransfer;

/**
 * @method UserDependencyContainer getDependencyContainer()
 */
class UserFacade extends AbstractFacade
{

    public function install()
    {
        $this->getDependencyContainer()
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
        return $this->getDependencyContainer()
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
        return $this->getDependencyContainer()
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
        return $this->getDependencyContainer()
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
        return $this->getDependencyContainer()
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
        return $this->getDependencyContainer()
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
        return $this->getDependencyContainer()
            ->getUserModel()
            ->setCurrentUser($user);
    }

    /**
     * @return UserTransfer
     */
    public function getCurrentUser()
    {
        return $this->getDependencyContainer()
            ->getUserModel()
            ->getCurrentUser();
    }

    /**
     * @return bool
     */
    public function hasCurrentUser()
    {
        return $this->getDependencyContainer()
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
        return $this->getDependencyContainer()
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
        return $this->getDependencyContainer()
            ->getUserModel()
            ->isSystemUser($user);
    }

    /**
     * @return CollectionTransfer
     */
    public function getSystemUsers()
    {
        return $this->getDependencyContainer()
            ->getUserModel()
            ->getSystemUsers();
    }

    /**
     * @return UserTransfer
     */
    public function getUsers()
    {
        return $this->getDependencyContainer()
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
        return $this->getDependencyContainer()
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
        return $this->getDependencyContainer()->getUserModel()->activateUser($idUser);
    }

    /**
     * @param int $idUser
     *
     * @return bool
     */
    public function deactivateUser($idUser)
    {
        return $this->getDependencyContainer()->getUserModel()->deactivateUser($idUser);
    }

}
