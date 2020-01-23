<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\User;

use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserResponseTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\User\Persistence\Map\SpyUserTableMap;
use Spryker\Zed\Merchant\MerchantConfig;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToAuthFacadeInterface;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface;

class UserWriter implements UserWriterInterface
{
    protected const MERCHANT_USER_ACTIVATION_FAIL = 'Merchant user activation fail.';
    protected const MERCHANT_USER_DEACTIVATION_FAIL = 'Merchant user deactivation fail.';

    /**
     * @var \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToAuthFacadeInterface
     */
    private $authFacade;

    /**
     * @var \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface
     */
    private $userFacade;

    /**
     * @param \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToAuthFacadeInterface $authFacade
     * @param \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface $userFacade
     */
    public function __construct(
        MerchantUserToAuthFacadeInterface $authFacade,
        MerchantUserToUserFacadeInterface $userFacade
    ) {

        $this->authFacade = $authFacade;
        $this->userFacade = $userFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getUserByMerchant(MerchantTransfer $merchantTransfer): UserTransfer
    {
        return $this->userFacade->getUserByUsername($merchantTransfer->getEmail());
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
     * @param \Generated\Shared\Transfer\MerchantTransfer $updatedMerchantTransfer
     * @param \Generated\Shared\Transfer\MerchantUserResponseTransfer $merchantUserTransferResponse
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    public function updateUserStatus(
        MerchantTransfer $updatedMerchantTransfer,
        MerchantUserResponseTransfer $merchantUserTransferResponse
    ): MerchantUserResponseTransfer {
        $currentMerchantStatus = $updatedMerchantTransfer->getStatus();
        $userTransfer = $merchantUserTransferResponse->getMerchantUser()->getUser();

        if ($userTransfer->getStatus() === SpyUserTableMap::COL_STATUS_BLOCKED &&
            $currentMerchantStatus === MerchantConfig::STATUS_APPROVED
        ) {
            return $this->activateUser($userTransfer, $merchantUserTransferResponse);
        }

        if ($userTransfer->getStatus() === SpyUserTableMap::COL_STATUS_ACTIVE &&
            $currentMerchantStatus === MerchantConfig::STATUS_DENIED
        ) {
            return $this->deactivateUser($userTransfer, $merchantUserTransferResponse);
        }

        return $merchantUserTransferResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getUserByMerchantUser(MerchantUserTransfer $merchantUserTransfer): UserTransfer
    {
        return $this->userFacade->getUserById($merchantUserTransfer->getIdUser());
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param \Generated\Shared\Transfer\MerchantUserResponseTransfer $merchantUserTransferResponse
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    protected function deactivateUser(
        UserTransfer $userTransfer,
        MerchantUserResponseTransfer $merchantUserTransferResponse
    ): MerchantUserResponseTransfer {
        $isDeactivated = $this->userFacade->deactivateUser($userTransfer->getIdUser());

        if ($isDeactivated === false) {
            $merchantUserTransferResponse->setIsSuccess(false)
                ->addError((new MessageTransfer())->setMessage(static::MERCHANT_USER_ACTIVATION_FAIL));
        }

        return $merchantUserTransferResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param \Generated\Shared\Transfer\MerchantUserResponseTransfer $merchantUserTransferResponse
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    protected function activateUser(
        UserTransfer $userTransfer,
        MerchantUserResponseTransfer $merchantUserTransferResponse
    ): MerchantUserResponseTransfer {
        $isActivated = $this->userFacade->activateUser($userTransfer->getIdUser());

        $isPasswordReset = $this->authFacade->requestPasswordReset($userTransfer->getUsername());

        if ($isActivated === false || $isPasswordReset === false) {
            $merchantUserTransferResponse->setIsSuccess(false)
                ->addError((new MessageTransfer())->setMessage(static::MERCHANT_USER_ACTIVATION_FAIL));
        }

        return $merchantUserTransferResponse;
    }
}
