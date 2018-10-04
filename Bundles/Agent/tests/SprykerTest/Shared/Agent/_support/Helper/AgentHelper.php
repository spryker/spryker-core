<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Agent\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\UserBuilder;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\User\Business\UserFacadeInterface;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class AgentHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function registerAgent(): UserTransfer
    {
        return $this->getUserFacade()
            ->createUser($this->createAgent());
    }

    /**
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function createAgent(): UserTransfer
    {
        $userTransfer = (new UserBuilder())->build();
        $userTransfer->setIsAgent(true);

        return $userTransfer;
    }

    /**
     * @return \Spryker\Zed\User\Business\UserFacadeInterface
     */
    protected function getUserFacade(): UserFacadeInterface
    {
        return $this->getLocatorHelper()->getLocator()->user()->facade();
    }
}
