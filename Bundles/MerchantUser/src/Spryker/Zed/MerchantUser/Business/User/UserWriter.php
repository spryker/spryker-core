<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\User;

use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\User\Persistence\Map\SpyUserTableMap;
use Spryker\Zed\Merchant\MerchantConfig;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToAuthFacadeInterface;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface;

class UserWriter implements UserWriterInterface
{
    /**
     * @var \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface
     */
    protected $userFacade;

    /**
     * @var \Spryker\Zed\MerchantUser\Business\User\UserReaderInterface
     */
    protected $userReader;

    /**
     * @var \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToAuthFacadeInterface
     */
    private $authFacade;

    /**
     * @param \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface $userFacade
     * @param \Spryker\Zed\MerchantUser\Business\User\UserReaderInterface $userReader
     * @param \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToAuthFacadeInterface $authFacade
     */
    public function __construct(
        MerchantUserToUserFacadeInterface $userFacade,
        UserReaderInterface $userReader,
        MerchantUserToAuthFacadeInterface $authFacade
    ) {
        $this->userFacade = $userFacade;
        $this->userReader = $userReader;
        $this->authFacade = $authFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function syncUserWithMerchant(
        MerchantTransfer $merchantTransfer,
        MerchantUserTransfer $merchantUserTransfer
    ): UserTransfer {
        $merchantTransfer->requireMerchantProfile();

        $userTransfer = $this->userReader->getUserByMerchantUser($merchantUserTransfer);

        $usersStatusBeforeUpdate = $userTransfer->getStatus();

        $userTransfer
            ->setFirstName($merchantTransfer->getMerchantProfile()->getContactPersonFirstName())
            ->setLastName($merchantTransfer->getMerchantProfile()->getContactPersonLastName())
            ->setUsername($merchantTransfer->getEmail());

        $userTransfer = $this->setUserStatusByMerchantStatus($userTransfer, $merchantTransfer);

        $userTransfer = $this->updateUser($userTransfer);

        if ($userTransfer->getStatus() === SpyUserTableMap::COL_STATUS_ACTIVE
            && $usersStatusBeforeUpdate !== $userTransfer->getStatus()
        ) {
            $this->authFacade->requestPasswordReset($userTransfer->getUsername());
        }

        return $userTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function updateUser(UserTransfer $userTransfer): UserTransfer
    {
        return $this->userFacade->updateUser($userTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function createUser(UserTransfer $userTransfer): UserTransfer
    {
        return $this->userFacade->createUser($userTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function setUserStatusByMerchantStatus(
        UserTransfer $userTransfer,
        MerchantTransfer $merchantUserTransfer
    ): UserTransfer {
        $userTransfer->setStatus(SpyUserTableMap::COL_STATUS_BLOCKED);

        if ($merchantUserTransfer->getStatus() === MerchantConfig::STATUS_APPROVED) {
            $userTransfer->setStatus(SpyUserTableMap::COL_STATUS_ACTIVE);
        }

        return $userTransfer;
    }
}
