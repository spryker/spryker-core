<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\User\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\UserBuilder;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\User\Business\UserFacadeInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class UserDataHelper extends Module
{
    use LocatorHelperTrait;
    use DataCleanupHelperTrait;

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function haveUser(array $override = []): UserTransfer
    {
        /** @var \Generated\Shared\Transfer\UserTransfer $userTransfer */
        $userTransfer = (new UserBuilder($override))->build();
        $userTransfer = $this->getUserFacade()->createUser($userTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($userTransfer): void {
            $this->cleanupUser($userTransfer);
        });

        return $userTransfer;
    }

    /**
     * @return \Spryker\Zed\User\Business\UserFacadeInterface
     */
    private function getUserFacade(): UserFacadeInterface
    {
        return $this->getLocatorHelper()->getLocator()->user()->facade();
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return void
     */
    protected function cleanupUser(UserTransfer $userTransfer): void
    {
        $this->getUserFacade()->removeUser($userTransfer->getIdUser());
    }
}
