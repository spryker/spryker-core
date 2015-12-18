<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Auth\Dependency\Facade;

use Generated\Shared\Transfer\UserTransfer;

interface AuthToUserInterface
{

    /**
     * @param UserTransfer $user
     *
     * @return bool
     */
    public function isSystemUser(UserTransfer $user);

    /**
     * @param string $username
     *
     * @return UserTransfer
     */
    public function getUserByUsername($username);

    /**
     * @return bool
     */
    public function hasCurrentUser();

    /**
     * @return UserTransfer
     */
    public function getCurrentUser();

    /**
     * @param string $username
     *
     * @return bool
     */
    public function hasUserByUsername($username);

    /**
     * @param string $password
     * @param string $hash
     *
     * @return bool
     */
    public function isValidPassword($password, $hash);

    /**
     * @param UserTransfer $user
     *
     * @throws \Spryker\Zed\User\Business\Exception\UserNotFoundException
     *
     * @return UserTransfer
     */
    public function updateUser(UserTransfer $user);

    /**
     * @param UserTransfer $user
     *
     * @return mixed
     */
    public function setCurrentUser(UserTransfer $user);

    /**
     * @param int $idUser
     *
     * @return UserTransfer
     */
    public function getUserById($idUser);

}
