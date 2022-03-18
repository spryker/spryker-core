<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SessionUserValidation\Dependency\Facade;

use Generated\Shared\Transfer\UserTransfer;

class SessionUserValidationToUserFacadeBridge implements SessionUserValidationToUserFacadeInterface
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
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getCurrentUser(): UserTransfer
    {
        return $this->userFacade->getCurrentUser();
    }

    /**
     * @param string $username
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getUserByUsername(string $username): UserTransfer
    {
        return $this->userFacade->getUserByUsername($username);
    }

    /**
     * @return bool
     */
    public function hasCurrentUser(): bool
    {
        return $this->userFacade->hasCurrentUser();
    }
}
