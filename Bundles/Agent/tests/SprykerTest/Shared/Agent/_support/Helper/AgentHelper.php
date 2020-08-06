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
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class AgentHelper extends Module
{
    use LocatorHelperTrait;
    use DataCleanupHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function haveRegisteredAgent(array $seedData = []): UserTransfer
    {
        $userTransfer = $this->createAgent($seedData);
        $userTransfer = $this->registerAgent($userTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($userTransfer) {
            $this->debug(sprintf('Deactivating User: %s', $userTransfer->getUsername()));
            $this->getUserFacade()->deactivateUser($userTransfer->getIdUser());
        });

        return $userTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer|null $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function registerAgent(?UserTransfer $userTransfer = null): UserTransfer
    {
        return $this->getUserFacade()
            ->createUser($userTransfer ?? $this->createAgent());
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function createAgent(array $seedData = []): UserTransfer
    {
        $userTransfer = (new UserBuilder($seedData))->build();
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
