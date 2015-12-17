<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Auth\Dependency\Facade;

use Generated\Shared\Transfer\UserTransfer;

class AuthToUserBridge implements AuthToUserInterface
{

    /**
     * @var \Spryker\Zed\User\Business\UserFacade
     */
    protected $userFacade;

    /**
     * @param \Spryker\Zed\User\Business\UserFacade $userFacade
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

}
