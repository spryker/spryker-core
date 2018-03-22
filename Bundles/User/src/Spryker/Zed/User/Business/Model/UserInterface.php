<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Business\Model;

use Generated\Shared\Transfer\UserTransfer;

interface UserInterface
{
    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $username
     * @param string $password
     *
     * @throws \Spryker\Zed\User\Business\Exception\UsernameExistsException
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function addUser($firstName, $lastName, $username, $password);

    /**
     * @param string $password
     *
     * @return string
     */
    public function encryptPassword($password);

    /**
     * @param string $password
     * @param string $hash
     *
     * @return bool
     */
    public function validatePassword($password, $hash);

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @throws \Spryker\Zed\User\Business\Exception\UserNotFoundException
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function save(UserTransfer $userTransfer);

    /**
     * @param string $username
     *
     * @return bool
     */
    public function hasUserByUsername($username);

    /**
     * @param string $username
     *
     * @return bool
     */
    public function hasActiveUserByUsername($username);

    /**
     * @param int $idUser
     *
     * @return bool
     */
    public function hasUserById($idUser);

    /**
     * @param string $username
     *
     * @throws \Spryker\Zed\User\Business\Exception\UserNotFoundException
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getUserByUsername($username);

    /**
     * @param int $id
     *
     * @throws \Spryker\Zed\User\Business\Exception\UserNotFoundException
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getUserById($id);

    /**
     * @param int $id
     *
     * @throws \Spryker\Zed\User\Business\Exception\UserNotFoundException
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getActiveUserById($id);

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $user
     *
     * @return mixed
     */
    public function setCurrentUser(UserTransfer $user);

    /**
     * @return bool
     */
    public function hasCurrentUser();

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $user
     *
     * @return bool
     */
    public function isSystemUser(UserTransfer $user);

    /**
     * @return \Generated\Shared\Transfer\CollectionTransfer
     */
    public function getSystemUsers();

    /**
     * @throws \Spryker\Zed\User\Business\Exception\UserNotFoundException
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getCurrentUser();

    /**
     * @param int $idUser
     *
     * @return bool
     */
    public function activateUser($idUser);

    /**
     * @param int $idUser
     *
     * @return bool
     */
    public function deactivateUser($idUser);
}
