<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Business;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\User\Business\UserBusinessFactory getFactory()
 */
class UserFacade extends AbstractFacade implements UserFacadeInterface
{
    /**
     * @api
     *
     * @return void
     */
    public function install()
    {
        $this->getFactory()
            ->createInstallerModel()
            ->install();
    }

    /**
     * @api
     *
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
     * @api
     *
     * @param string $username
     *
     * @return bool
     */
    public function hasActiveUserByUsername($username)
    {
        return $this->getFactory()
            ->createUserModel()
            ->hasActiveUserByUsername($username);
    }

    /**
     * @api
     *
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
     * @api
     *
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    public function findUserById(int $idUser): ?UserTransfer
    {
        return $this->getFactory()
            ->createUserModel()
            ->findUserById($idUser);
    }

    /**
     * @api
     *
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getActiveUserById($idUser)
    {
        return $this->getFactory()
            ->createUserModel()
            ->getActiveUserById($idUser);
    }

    /**
     * @api
     *
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
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function createUser(UserTransfer $userTransfer): UserTransfer
    {
        return $this->getFactory()
            ->createUserModel()
            ->createUser($userTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $user
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
     * @api
     *
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
     * @api
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getCurrentUser()
    {
        return $this->getFactory()
            ->createUserModel()
            ->getCurrentUser();
    }

    /**
     * @api
     *
     * @return bool
     */
    public function hasCurrentUser()
    {
        return $this->getFactory()
            ->createUserModel()
            ->hasCurrentUser();
    }

    /**
     * @api
     *
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
     * @api
     *
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
     * @api
     *
     * @return \Generated\Shared\Transfer\CollectionTransfer
     */
    public function getSystemUsers()
    {
        return $this->getFactory()
            ->createUserModel()
            ->getSystemUsers();
    }

    /**
     * @api
     *
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function removeUser($idUser)
    {
        return $this->getFactory()
            ->createUserModel()
            ->removeUser($idUser);
    }

    /**
     * @api
     *
     * @param int $idUser
     *
     * @return bool
     */
    public function activateUser($idUser)
    {
        return $this->getFactory()->createUserModel()->activateUser($idUser);
    }

    /**
     * @api
     *
     * @param int $idUser
     *
     * @return bool
     */
    public function deactivateUser($idUser)
    {
        return $this->getFactory()->createUserModel()->deactivateUser($idUser);
    }
}
