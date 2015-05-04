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
     * @return TransferUser
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
     * @param TransferUser $data
     *
     * @return TransferUser
     * @throws UserNotFoundException
     */
    public function save(TransferUser $data);

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
     * @return TransferUser
     * @throws UserNotFoundException
     */
    public function getUserByUsername($username);

    /**
     * @param int $id
     *
     * @return TransferUser
     * @throws UserNotFoundException
     */
    public function getUserById($id);

    /**
     * @param TransferUser $user
     *
     * @return TransferUser
     */
    public function setCurrentUser(TransferUser $user);

    /**
     * @return bool
     */
    public function hasCurrentUser();

    /**
     * @param TransferUser $user
     *
     * @return bool
     */
    public function isSystemUser(TransferUser $user);

    /**
     * @return UserCollection
     */
    public function getSystemUsers();

    /**
     * @return TransferUser
     * @throws UserNotFoundException
     */
    public function getCurrentUser();
}
