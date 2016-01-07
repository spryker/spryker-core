<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Auth\Dependency\Facade;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\User\Business\UserFacade;

class AuthToUserBridge implements AuthToUserInterface
{

    /**
     * @var UserFacade
     */
    protected $userFacade;

    /**
     * @param UserFacade $userFacade
     */
    public function __construct($userFacade)
    {
        $this->userFacade = $userFacade;
    }

    /**
     * @param UserTransfer $user
     *
     * @return bool
     */
    public function isSystemUser(UserTransfer $user)
    {
        return $this->userFacade->isSystemUser($user);
    }

    /**
     * @param string $username
     *
     * @return UserTransfer
     */
    public function getUserByUsername($username)
    {
        return $this->userFacade->getUserByUsername($username);
    }

    /**
     * @return bool
     */
    public function hasCurrentUser()
    {
        return $this->userFacade->hasCurrentUser();
    }

    /**
     * @return UserTransfer
     */
    public function getCurrentUser()
    {
        return $this->userFacade->getCurrentUser();
    }

    /**
     * @param string $username
     *
     * @return bool
     */
    public function hasUserByUsername($username)
    {
        return $this->userFacade->hasUserByUsername($username);
    }

    /**
     * @param string $password
     * @param string $hash
     *
     * @return bool
     */
    public function isValidPassword($password, $hash)
    {
        return $this->userFacade->isValidPassword($password, $hash);
    }

    /**
     * @param UserTransfer $user
     *
     * @return UserTransfer
     */
    public function updateUser(UserTransfer $user)
    {
        return $this->userFacade->updateUser($user);
    }

    /**
     * @param UserTransfer $user
     *
     * @return mixed
     */
    public function setCurrentUser(UserTransfer $user)
    {
        return $this->userFacade->setCurrentUser($user);
    }

    /**
     * @param int $idUser
     *
     * @return UserTransfer
     */
    public function getUserById($idUser)
    {
        return $this->userFacade->getUserById($idUser);
    }

}
