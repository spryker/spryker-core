<?php

namespace SprykerFeature\Zed\User\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\Transfer\UserUserTransfer;

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
     * @return UserUserTransfer
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
     * @return UserUserTransfer
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
     * @return UserUserTransfer
     */
    public function addUser($firstName, $lastName, $username, $password)
    {
        return $this->getDependencyContainer()
            ->getUserModel()
            ->addUser($firstName, $lastName, $username, $password);
    }

    /**
     * @param UserUserTransfer $user
     *
     * @return UserUserTransfer
     * @throws Exception\UserNotFoundException
     */
    public function updateUser(UserUserTransfer $user)
    {
        return $this->getDependencyContainer()
            ->getUserModel()
            ->save($user);
    }

    /**
     * @param UserUserTransfer $user
     *
     * @return mixed
     */
    public function setCurrentUser(UserUserTransfer $user)
    {
        return $this->getDependencyContainer()
            ->getUserModel()
            ->setCurrentUser($user);
    }

    /**
     * @return UserUserTransfer
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
     * @param UserUserTransfer $user
     *
     * @return bool
     */
    public function isSystemUser(UserUserTransfer $user)
    {
        return $this->getDependencyContainer()
            ->getUserModel()
            ->isSystemUser($user);
    }

    /**
     * @return UserUserTransfer
     */
    public function getSystemUsers()
    {
        return $this->getDependencyContainer()
            ->getUserModel()
            ->getSystemUsers();
    }

    /**
     * @return UserUserTransfer
     */
    public function getUsers()
    {
        return $this->getDependencyContainer()
            ->getUserModel()
            ->getUsers();
    }

    /**
     * @param $idUser
     */
    public function removeUser($idUser)
    {
        $this->getDependencyContainer()
            ->getUserModel()
            ->removeUser($idUser);
    }
}
