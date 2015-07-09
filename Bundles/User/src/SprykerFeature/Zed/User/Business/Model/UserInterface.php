<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\User\Business\Model;

use Generated\Shared\Transfer\CollectionTransfer;
use Generated\Shared\Transfer\UserTransfer;
use SprykerFeature\Zed\User\Business\Exception\UsernameExistsException;
use SprykerFeature\Zed\User\Business\Exception\UserNotFoundException;

interface UserInterface
{

    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $username
     * @param string $password
     *
     * @throws UsernameExistsException
     *
     * @return UserTransfer
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
     * @param UserTransfer $user
     *
     * @throws UserNotFoundException
     *
     * @return UserTransfer
     */
    public function save(UserTransfer $user);

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
     * @throws UserNotFoundException
     *
     * @return UserTransfer
     */
    public function getUserByUsername($username);

    /**
     * @param int $id
     *
     * @throws UserNotFoundException
     *
     * @return UserTransfer
     */
    public function getUserById($id);

    /**
     * @param UserTransfer $user
     *
     * @return UserTransfer
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
     * @return CollectionTransfer
     */
    public function getSystemUsers();

    /**
     * @throws UserNotFoundException
     *
     * @return UserTransfer
     */
    public function getCurrentUser();

}
