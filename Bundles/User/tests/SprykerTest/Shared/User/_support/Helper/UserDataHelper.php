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
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class UserDataHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function haveUser(array $override = []): UserTransfer
    {
        $userTransfer = (new UserBuilder($override))->build();
        $userTransfer = $this->getUserFacade()->addUser(
            $userTransfer->getFirstName(),
            $userTransfer->getLastName(),
            $userTransfer->getUsername(),
            $userTransfer->getPassword()
        );

        return $userTransfer;
    }

    /**
     * @return \Spryker\Zed\User\Business\UserFacadeInterface
     */
    private function getUserFacade(): UserFacadeInterface
    {
        return $this->getLocatorHelper()->getLocator()->user()->facade();
    }
}
