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
            ->createInstallerModel()
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
            ->createUserModel()
            ->hasUserByUsername($username);
    }

    /**
     * @param string $username
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getUserByUsername($username)
    {
        return $this->getFactory()
            ->createUserModel()
            ->getUserByUsername($username);
    }

    /**
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getUserById($idUser)
    {
        return $this->getFactory()
            ->createUserModel()
            ->getUserById($idUser);
    }

    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $username
     * @param string $password
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function addUser($firstName, $lastName, $username, $password)
    {
        return $this->getFactory()
            ->createUserModel()
            ->addUser($firstName, $lastName, $username, $password);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $user
     *
     * @throws Exception\UserNotFoundException
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function updateUser(UserTransfer $user)
    {
        return $this->getFactory()
            ->createUserModel()
            ->save($user);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $user
     *
     * @return mixed
     */
    public function setCurrentUser(UserTransfer $user)
    {
        return $this->getFactory()
            ->createUserModel()
            ->setCurrentUser($user);
    }

    /**
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getCurrentUser()
    {
        return $this->getFactory()
            ->createUserModel()
            ->getCurrentUser();
    }

    /**
     * @return bool
     */
    public function hasCurrentUser()
    {
        return $this->getFactory()
            ->createUserModel()
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
            ->createUserModel()
            ->validatePassword($password, $hash);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $user
     *
     * @return bool
     */
    public function isSystemUser(UserTransfer $user)
    {
        return $this->getFactory()
            ->createUserModel()
            ->isSystemUser($user);
    }

    /**
     * @return \Generated\Shared\Transfer\CollectionTransfer
     */
    public function getSystemUsers()
    {
        return $this->getFactory()
            ->createUserModel()
            ->getSystemUsers();
    }

    /**
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getUsers()
    {
        return $this->getFactory()
            ->createUserModel()
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
            ->createUserModel()
            ->removeUser($idUser);
    }

    /**
     * @param int $idUser
     *
     * @return bool
     */
    public function activateUser($idUser)
    {
        return $this->getFactory()->createUserModel()->activateUser($idUser);
    }

    /**
     * @param int $idUser
     *
     * @return bool
     */
    public function deactivateUser($idUser)
    {
        return $this->getFactory()->createUserModel()->deactivateUser($idUser);
    }

}
