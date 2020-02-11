<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\MerchantUser;

use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantUserResponseTransfer;
use Generated\Shared\Transfer\UserTransfer;
use InvalidArgumentException;
use Spryker\Zed\MerchantUser\Business\User\UserMapperInterface;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToAuthFacadeInterface;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface;
use Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface;

class MerchantUserUpdater implements MerchantUserUpdaterInterface
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
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_APPROVED
     */
    protected const MERCHANT_STATUS_APPROVED = 'approved';
    /**
     * @var \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToAuthFacadeInterface
     */
    protected $authFacade;

    /**
     * @var \Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface
     */
    protected $merchantUserRepository;

    /**
     * @var \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface
     */
    protected $userFacade;

    /**
     * @var \Spryker\Zed\MerchantUser\Business\User\UserMapperInterface
     */
    protected $userMapper;

    /**
     * @param \Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface $merchantUserRepository
     * @param \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface $userFacade
     * @param \Spryker\Zed\MerchantUser\Business\User\UserMapperInterface $userMapper
     * @param \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToAuthFacadeInterface $authFacade
     */
    public function __construct(
        MerchantUserRepositoryInterface $merchantUserRepository,
        MerchantUserToUserFacadeInterface $userFacade,
        UserMapperInterface $userMapper,
        MerchantUserToAuthFacadeInterface $authFacade
    ) {
        $this->merchantUserRepository = $merchantUserRepository;
        $this->userFacade = $userFacade;
        $this->userMapper = $userMapper;
        $this->authFacade = $authFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @throws \InvalidArgumentException
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    public function updateMerchantAdmin(MerchantTransfer $merchantTransfer): MerchantUserResponseTransfer
    {
        $merchantTransfer->requireMerchantProfile();

        $merchantUserTransfer = $this->merchantUserRepository->findOne(
            (new MerchantUserCriteriaFilterTransfer())->setIdMerchant($merchantTransfer->getIdMerchant())
        );
        if (!$merchantUserTransfer) {
            throw new InvalidArgumentException(
                sprintf('Could not find Merchant Admin by Merchant id %d', $merchantTransfer->getIdMerchant())
            );
        }

        $userTransfer = $this->userFacade->getUserById($merchantUserTransfer->getIdUser());
        $originalUserTransfer = (new UserTransfer())->fromArray($userTransfer->toArray());
        $userTransfer = $this->userMapper->mapMerchantTransferToUserTransfer($merchantTransfer, $userTransfer);

        $userTransfer = $this->setUserStatusByMerchantStatus($userTransfer, $merchantTransfer);

        $userTransfer = $this->userFacade->updateUser($userTransfer);

        $merchantUserTransfer->setMerchant($merchantTransfer);

        $this->resetUserPassword($originalUserTransfer, $userTransfer);

        return (new MerchantUserResponseTransfer())->setIsSuccessful(true)->setMerchantUser($merchantUserTransfer);
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

        if ($merchantTransfer->getStatus() === static::MERCHANT_STATUS_APPROVED) {
            $userTransfer->setStatus(static::USER_STATUS_ACTIVE);
        }

        return $userTransfer;
    }
}
