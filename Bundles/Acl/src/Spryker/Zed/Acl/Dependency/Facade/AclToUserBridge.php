<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Acl\Dependency\Facade;

use Generated\Shared\Transfer\UserTransfer;

class AclToUserBridge implements AclToUserInterface
{

    /**
     * @var \Spryker\Zed\User\Business\UserFacade
     */
    protected $userFacade;

    /**
     * AclToUserBridge constructor.
     *
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
