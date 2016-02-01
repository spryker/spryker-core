<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\User\Business\Model;

use Generated\Shared\Transfer\CollectionTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\User\Business\Exception\UsernameExistsException;
use Spryker\Zed\User\Business\Exception\UserNotFoundException;

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
     * @param UserTransfer $userTransfer
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
     * @param UserTransfer $user
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function setCurrentUser(UserTransfer $user);

    /**
     * @return bool
     */
    public function hasCurrentUser();

    /**
     * @param UserTransfer $user
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
