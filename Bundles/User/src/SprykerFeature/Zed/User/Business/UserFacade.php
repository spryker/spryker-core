<?php

namespace SprykerFeature\Zed\User\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Shared\User\Transfer\User;
use SprykerFeature\Shared\User\Transfer\UserCollection;

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
     * @return User
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
     * @return User
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
     * @return User
     */
    public function addUser($firstName, $lastName, $username, $password)
    {
        return $this->getDependencyContainer()
            ->getUserModel()
            ->addUser($firstName, $lastName, $username, $password);
    }

    /**
     * @param User $user
     *
     * @return User
     * @throws Exception\UserNotFoundException
     */
    public function updateUser(User $user)
    {
        return $this->getDependencyContainer()
            ->getUserModel()
            ->save($user);
    }

    /**
     * @param User $user
     *
     * @return mixed
     */
    public function setCurrentUser(User $user)
    {
        return $this->getDependencyContainer()
            ->getUserModel()
            ->setCurrentUser($user);
    }

    /**
     * @return User
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
     * @param User $user
     *
     * @return bool
     */
    public function isSystemUser(User $user)
    {
        return $this->getDependencyContainer()
            ->getUserModel()
            ->isSystemUser($user);
    }

    /**
     * @return UserCollection
     */
    public function getSystemUsers()
    {
        return $this->getDependencyContainer()
            ->getUserModel()
            ->getSystemUsers();
    }

    /**
     * @return UserCollection
     */
    public function getUsers()
    {
        return $this->getDependencyContainer()
            ->getUserModel()
            ->getUsers();
    }

    /**
     * @return void
     */
    public function removeUser($idUser)
    {
        $this->getDependencyContainer()
            ->getUserModel()
            ->removeUser($idUser);
    }
}
