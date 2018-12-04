<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Business;

use Generated\Shared\Transfer\UserTransfer;

interface UserFacadeInterface
{
    /**
     * @api
     *
     * @return void
     */
    public function install();

    /**
     * @api
     *
     * @param string $username
     *
     * @return bool
     */
    public function hasUserByUsername($username);

    /**
     * @api
     *
     * @param string $username
     *
     * @return bool
     */
    public function hasActiveUserByUsername($username);

    /**
     * @api
     *
     * @param string $username
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getUserByUsername($username);

    /**
     * @api
     *
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getUserById($idUser);

    /**
     * Specification:
     * - Returns user by id if it exists.
     *
     * @api
     *
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    public function findUserById(int $idUser): ?UserTransfer;

    /**
     * @api
     *
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getActiveUserById($idUser);

    /**
     * @api
     *
     * @deprecated Use \Spryker\Zed\User\Business\UserFacadeInterface::createUser instead.
     *
     * @param string $firstName
     * @param string $lastName
     * @param string $username
     * @param string $password
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function addUser($firstName, $lastName, $username, $password);

    /**
     * Specification:
     *  - Creates user from user transfer.
     *  - Executes user post save plugins.
     *  - Throws exception if username exist.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function createUser(UserTransfer $userTransfer): UserTransfer;

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $user
     *
     * @throws \Spryker\Zed\User\Business\Exception\UserNotFoundException
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function updateUser(UserTransfer $user);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $user
     *
     * @return mixed
     */
    public function setCurrentUser(UserTransfer $user);

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getCurrentUser();

    /**
     * @api
     *
     * @return bool
     */
    public function hasCurrentUser();

    /**
     * @api
     *
     * @param string $password
     * @param string $hash
     *
     * @return bool
     */
    public function isValidPassword($password, $hash);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $user
     *
     * @return bool
     */
    public function isSystemUser(UserTransfer $user);

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\CollectionTransfer
     */
    public function getSystemUsers();

    /**
     * @api
     *
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function removeUser($idUser);

    /**
     * @api
     *
     * @param int $idUser
     *
     * @return bool
     */
    public function activateUser($idUser);

    /**
     * @api
     *
     * @param int $idUser
     *
     * @return bool
     */
    public function deactivateUser($idUser);
}
