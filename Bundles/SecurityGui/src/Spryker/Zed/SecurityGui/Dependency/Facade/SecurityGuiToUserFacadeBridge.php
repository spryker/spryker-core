<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityGui\Dependency\Facade;

use Generated\Shared\Transfer\UserTransfer;

class SecurityGuiToUserFacadeBridge implements SecurityGuiToUserFacadeInterface
{
    /**
     * @var \Spryker\Zed\User\Business\UserFacadeInterface
     */
    protected $userFacade;

    /**
     * @param \Spryker\Zed\User\Business\UserFacadeInterface $userFacade
     */
    public function __construct($userFacade)
    {
        $this->userFacade = $userFacade;
    }

    /**
     * @param string $username
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getUserByUsername($username)
    {
        return $this->userFacade->getUserByUsername($username);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $user
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function updateUser(UserTransfer $user)
    {
        return $this->userFacade->updateUser($user);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $user
     *
     * @return mixed
     */
    public function setCurrentUser(UserTransfer $user)
    {
        return $this->userFacade->setCurrentUser($user);
    }

    /**
     * @param string $username
     *
     * @return bool
     */
    public function hasActiveUserByUsername($username)
    {
        return $this->userFacade->hasActiveUserByUsername($username);
    }
}
