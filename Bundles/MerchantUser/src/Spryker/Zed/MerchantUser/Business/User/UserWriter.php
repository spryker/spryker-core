<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\User;

use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Merchant\MerchantConfig;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToAuthFacadeInterface;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface;

class UserWriter implements UserWriterInterface
{
    /**
     * @see \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS_BLOCKED
     */
    protected const USER_STATUS_BLOCKED = 'blocked';
    /**
     * @see \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS_ACTIVE
     */
    protected const USER_STATUS_ACTIVE = 'active';

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
    protected $authFacade;

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

        $originalUserTransfer = $this->userReader->getUserByMerchantUser($merchantUserTransfer);
        $userTransfer = clone $originalUserTransfer;

        $userTransfer
            ->setFirstName($merchantTransfer->getMerchantProfile()->getContactPersonFirstName())
            ->setLastName($merchantTransfer->getMerchantProfile()->getContactPersonLastName())
            ->setUsername($merchantTransfer->getEmail());

        $userTransfer = $this->setUserStatusByMerchantStatus($userTransfer, $merchantTransfer);

        $userTransfer = $this->updateUser($userTransfer);

        $this->resetUserPassword($originalUserTransfer, $userTransfer);

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
     * @param \Generated\Shared\Transfer\UserTransfer $originalUserTransfer
     * @param \Generated\Shared\Transfer\UserTransfer $updatedUserTransfer
     *
     * @return void
     */
    protected function resetUserPassword(UserTransfer $originalUserTransfer, UserTransfer $updatedUserTransfer): void
    {
        if ($updatedUserTransfer->getStatus() === static::USER_STATUS_ACTIVE
            && $originalUserTransfer->getStatus() !== $updatedUserTransfer->getStatus()
        ) {
            $this->authFacade->requestPasswordReset($updatedUserTransfer->getUsername());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function setUserStatusByMerchantStatus(
        UserTransfer $userTransfer,
        MerchantTransfer $merchantTransfer
    ): UserTransfer {
        $userTransfer->setStatus(static::USER_STATUS_BLOCKED);

        if ($merchantTransfer->getStatus() === MerchantConfig::STATUS_APPROVED) {
            $userTransfer->setStatus(static::USER_STATUS_ACTIVE);
        }

        return $userTransfer;
    }
}
