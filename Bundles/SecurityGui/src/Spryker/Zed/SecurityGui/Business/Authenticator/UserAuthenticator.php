<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityGui\Business\Authenticator;

use DateTime;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\SecurityGui\Dependency\Facade\SecurityGuiToUserFacadeInterface;

class UserAuthenticator implements UserAuthenticatorInterface
{
    /**
     * @var \Spryker\Zed\SecurityGui\Dependency\Facade\SecurityGuiToUserFacadeInterface
     */
    protected $userFacade;

    /**
     * @param \Spryker\Zed\SecurityGui\Dependency\Facade\SecurityGuiToUserFacadeInterface $userFacade
     */
    public function __construct(SecurityGuiToUserFacadeInterface $userFacade)
    {
        $this->userFacade = $userFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return void
     */
    public function authenticateUser(UserTransfer $userTransfer): void
    {
        $this->userFacade->setCurrentUser($userTransfer);
        $this->updateUserLastLoginDate($userTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return void
     */
    protected function updateUserLastLoginDate(UserTransfer $userTransfer): void
    {
        $userTransfer->setLastLogin((new DateTime())->format(DateTime::ATOM));
        $this->userFacade->updateUser(clone $userTransfer);
    }
}
