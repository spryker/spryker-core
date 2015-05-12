<?php

namespace SprykerFeature\Zed\User\Business\Model;

use Generated\Shared\Transfer\UserUserTransfer;
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
     * @return UserUserTransfer
     * @throws UsernameExistsException
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
     * @param UserUserTransfer $user
     *
     * @return UserUserTransfer
     * @throws UserNotFoundException
     */
    public function save(UserUserTransfer $user);

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
     * @return UserUserTransfer
     * @throws UserNotFoundException
     */
    public function getUserByUsername($username);

    /**
     * @param int $id
     *
     * @return UserUserTransfer
     * @throws UserNotFoundException
     */
    public function getUserById($id);

    /**
     * @param UserUserTransfer $user
     *
     * @return UserUserTransfer
     */
    public function setCurrentUser(UserUserTransfer $user);

    /**
     * @return bool
     */
    public function hasCurrentUser();

    /**
     * @param UserUserTransfer $user
     *
     * @return bool
     */
    public function isSystemUser(UserUserTransfer $user);

    /**
     * @return UserUserTransfer
     */
    public function getSystemUsers();

    /**
     * @return UserUserTransfer
     * @throws UserNotFoundException
     */
    public function getCurrentUser();
}
