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
     * @return void
     */
    public function install();

    /**
     * @param string $username
     *
     * @return bool
     */
    public function hasUserByUsername($username);

    /**
     * @param string $username
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getUserByUsername($username);

    /**
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getUserById($idUser);

    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $username
     * @param string $password
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function addUser($firstName, $lastName, $username, $password);

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $user
     *
     * @throws Exception\UserNotFoundException
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function updateUser(UserTransfer $user);

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $user
     *
     * @return mixed
     */
    public function setCurrentUser(UserTransfer $user);

    /**
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getCurrentUser();

    /**
     * @return bool
     */
    public function hasCurrentUser();

    /**
     * @param string $password
     * @param string $hash
     *
     * @return bool
     */
    public function isValidPassword($password, $hash);

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
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getUsers();

    /**
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function removeUser($idUser);

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
