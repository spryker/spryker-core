<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\MerchantUser;

use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantUserResponseTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\User\Persistence\Map\SpyUserTableMap;
use Spryker\Zed\Merchant\MerchantConfig;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToAuthFacadeInterface;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface;
use Spryker\Zed\MerchantUser\Dependency\Service\MerchantUserToUtilTextServiceInterface;
use Spryker\Zed\MerchantUser\MerchantUserConfig;
use Spryker\Zed\MerchantUser\Persistence\MerchantUserEntityManagerInterface;
use Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface;
use Spryker\Zed\User\Business\Exception\UserNotFoundException;

class MerchantUserWriter implements MerchantUserWriterInterface
{
    protected const USER_HAVE_ANOTHER_MERCHANT_ERROR_MESSAGE = 'A user with the same email is already connected to another merchant.';
    protected const MERCHANT_USER_NOT_FOUND_ERROR_MESSAGE = 'Merchant user relation was not found.';
    protected const MERCHANT_USER_ACTIVATION_FAIL = 'Merchant user activation fail';

    /**
     * @var \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface
     */
    protected $userFacade;

    /**
     * @var \Spryker\Zed\MerchantUser\Persistence\MerchantUserEntityManagerInterface
     */
    protected $merchantUserEntityManager;

    /**
     * @var \Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface
     */
    protected $merchantUserRepository;

    /**
     * @var \Spryker\Zed\MerchantUser\MerchantUserConfig
     */
    protected $merchantUserConfig;

    /**
     * @var \Spryker\Zed\MerchantUser\Dependency\Service\MerchantUserToUtilTextServiceInterface
     */
    protected $utilTextService;

    /**
     * @var \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToAuthFacadeInterface
     */
    private $authFacade;

    /**
     * @param \Spryker\Zed\MerchantUser\Persistence\MerchantUserEntityManagerInterface $merchantUserEntityManager
     * @param \Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface $merchantUserRepository
     * @param \Spryker\Zed\MerchantUser\MerchantUserConfig $merchantUserConfig
     * @param \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface $userFacade
     * @param \Spryker\Zed\MerchantUser\Dependency\Service\MerchantUserToUtilTextServiceInterface $utilTextService
     * @param \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToAuthFacadeInterface $authFacade
     */
    public function __construct(
        MerchantUserEntityManagerInterface $merchantUserEntityManager,
        MerchantUserRepositoryInterface $merchantUserRepository,
        MerchantUserConfig $merchantUserConfig,
        MerchantUserToUserFacadeInterface $userFacade,
        MerchantUserToUtilTextServiceInterface $utilTextService,
        MerchantUserToAuthFacadeInterface $authFacade
    ) {
        $this->userFacade = $userFacade;
        $this->merchantUserRepository = $merchantUserRepository;
        $this->merchantUserEntityManager = $merchantUserEntityManager;
        $this->merchantUserConfig = $merchantUserConfig;
        $this->utilTextService = $utilTextService;
        $this->authFacade = $authFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    public function createByMerchant(MerchantTransfer $merchantTransfer): MerchantUserResponseTransfer
    {
        $merchantTransfer->requireEmail()->requireMerchantProfile();

        $userTransfer = $this->resolveUserTransferByMerchant($merchantTransfer);
        if (!$this->merchantUserConfig->canUserHaveManyMerchants() && $this->hasUserAnotherMerchant($userTransfer, $merchantTransfer)) {
            return (new MerchantUserResponseTransfer())
                ->setIsSuccess(false)
                ->addError(
                    (new MessageTransfer())
                        ->setMessage(sprintf(static::USER_HAVE_ANOTHER_MERCHANT_ERROR_MESSAGE, $merchantTransfer->getEmail()))
                );
        }

        $merchantUserTransfer = $this->merchantUserEntityManager->createMerchantUser(
            (new MerchantUserTransfer())
                ->setIdMerchant($merchantTransfer->getIdMerchant())
                ->setIdUser($userTransfer->getIdUser())
        );
        $this->updateByMerchant($merchantUserTransfer, $merchantTransfer);

        return (new MerchantUserResponseTransfer())
            ->setIsSuccess(true)
            ->setMerchantUser($merchantUserTransfer->setUser($userTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    public function updateByMerchant(MerchantUserTransfer $merchantUserTransfer, MerchantTransfer $merchantTransfer): MerchantUserResponseTransfer
    {
        $userTransfer = $this->userFacade->updateUser($this->fillUserTransferFromMerchant(
            $this->userFacade->getUserById($merchantUserTransfer->getIdUser()),
            $merchantTransfer
        ));

        return (new MerchantUserResponseTransfer())
            ->setIsSuccess(true)
            ->setMerchantUser($merchantUserTransfer->setUser($userTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $originalMerchantTransfer
     * @param \Generated\Shared\Transfer\MerchantTransfer $updatedMerchantTransfer
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    public function syncUserWithMerchant(
        MerchantTransfer $originalMerchantTransfer,
        MerchantTransfer $updatedMerchantTransfer,
        MerchantUserTransfer $merchantUserTransfer
    ): MerchantUserResponseTransfer {
        $merchantUserTransferResponse = $this->updateByMerchant(
            $merchantUserTransfer,
            $updatedMerchantTransfer
        );

        if ($merchantUserTransferResponse->getIsSuccess() === true &&
            $this->isMerchantStatusChanged($originalMerchantTransfer, $updatedMerchantTransfer) === true) {
            $merchantUserTransferResponse = $this->updateUserStatus(
                $updatedMerchantTransfer,
                $merchantUserTransferResponse
            );
        }

        return $merchantUserTransferResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $updatedMerchantTransfer
     * @param \Generated\Shared\Transfer\MerchantUserResponseTransfer $merchantUserTransferResponse
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    protected function updateUserStatus(
        MerchantTransfer $updatedMerchantTransfer,
        MerchantUserResponseTransfer $merchantUserTransferResponse
    ): MerchantUserResponseTransfer {
        $currentMerchantStatus = $updatedMerchantTransfer->getStatus();
        $userTransfer = $merchantUserTransferResponse->getMerchantUser()->getUser();

        if ($userTransfer->getStatus() === SpyUserTableMap::COL_STATUS_BLOCKED &&
            $currentMerchantStatus === MerchantConfig::STATUS_APPROVED) {
            return $this->activateUser($userTransfer, $merchantUserTransferResponse);
        }

        if ($userTransfer->getStatus() === SpyUserTableMap::COL_STATUS_ACTIVE &&
            $currentMerchantStatus === MerchantConfig::STATUS_DENIED) {
            return $this->deactivateUser($userTransfer, $merchantUserTransferResponse);
        }

        return $merchantUserTransferResponse;
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

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $originalMerchantTransfer
     * @param \Generated\Shared\Transfer\MerchantTransfer $updatedMerchantTransfer
     *
     * @return bool
     */
    protected function isMerchantStatusChanged(
        MerchantTransfer $originalMerchantTransfer,
        MerchantTransfer $updatedMerchantTransfer
    ): bool {
        return $originalMerchantTransfer->getStatus() !== $updatedMerchantTransfer->getStatus();
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return bool
     */
    protected function hasUserAnotherMerchant(UserTransfer $userTransfer, MerchantTransfer $merchantTransfer): bool
    {
        $merchantUserTransfer = $this->merchantUserRepository->findOne(
            (new MerchantUserCriteriaFilterTransfer())->setIdUser($userTransfer->getIdUser())
        );

        if (!$merchantUserTransfer) {
            return false;
        }

        return $merchantUserTransfer->getIdMerchant() !== $merchantTransfer->getIdMerchant();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function resolveUserTransferByMerchant(MerchantTransfer $merchantTransfer): UserTransfer
    {
        try {
            return $this->userFacade->getUserByUsername($merchantTransfer->getEmail());
        } catch (UserNotFoundException $exception) {
            return $this->createUserForMerchant($merchantTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function createUserForMerchant(MerchantTransfer $merchantTransfer): UserTransfer
    {
        $userTransfer = $this->fillUserTransferFromMerchant(new UserTransfer(), $merchantTransfer)
            ->setPassword($this->utilTextService->generateRandomString(MerchantUserConfig::USER_CREATION_DEFAULT_PASSWORD_LENGTH))
            ->setStatus(MerchantUserConfig::USER_CREATION_DEFAULT_STATUS);

        return $this->userFacade->createUser($userTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function fillUserTransferFromMerchant(UserTransfer $userTransfer, MerchantTransfer $merchantTransfer): UserTransfer
    {
        return $userTransfer
            ->setFirstName($merchantTransfer->getMerchantProfile()->getContactPersonFirstName())
            ->setLastName($merchantTransfer->getMerchantProfile()->getContactPersonLastName())
            ->setUsername($merchantTransfer->getEmail());
    }
}
