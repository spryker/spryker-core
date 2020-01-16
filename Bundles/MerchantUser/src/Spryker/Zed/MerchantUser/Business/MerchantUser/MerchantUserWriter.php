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
use Spryker\Service\UtilText\UtilTextServiceInterface;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface;
use Spryker\Zed\MerchantUser\MerchantUserConfig;
use Spryker\Zed\MerchantUser\Persistence\MerchantUserEntityManagerInterface;
use Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface;
use Spryker\Zed\User\Business\Exception\UserNotFoundException;

class MerchantUserWriter implements MerchantUserWriterInterface
{
    protected const USER_HAVE_ANOTHER_MERCHANT_ERROR_MESSAGE = 'A user with the same email is already connected to another merchant.';
    protected const MERCHANT_USER_NOT_FOUND_ERROR_MESSAGE = 'Merchant user relation was not found.';

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
     * @var \Spryker\Service\UtilText\UtilTextServiceInterface
     */
    protected $utilTextService;

    /**
     * @param \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface $userFacade
     * @param \Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface $merchantUserRepository
     * @param \Spryker\Zed\MerchantUser\Persistence\MerchantUserEntityManagerInterface $merchantUserEntityManager
     * @param \Spryker\Zed\MerchantUser\MerchantUserConfig $merchantUserConfig
     * @param \Spryker\Service\UtilText\UtilTextServiceInterface $utilTextService
     */
    public function __construct(
        MerchantUserToUserFacadeInterface $userFacade,
        MerchantUserRepositoryInterface $merchantUserRepository,
        MerchantUserEntityManagerInterface $merchantUserEntityManager,
        MerchantUserConfig $merchantUserConfig,
        UtilTextServiceInterface $utilTextService
    ) {
        $this->userFacade = $userFacade;
        $this->merchantUserRepository = $merchantUserRepository;
        $this->merchantUserEntityManager = $merchantUserEntityManager;
        $this->merchantUserConfig = $merchantUserConfig;
        $this->utilTextService = $utilTextService;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    public function createByMerchant(MerchantTransfer $merchantTransfer): MerchantUserResponseTransfer
    {
        $merchantTransfer->requireEmail()->requireMerchantProfile();

        $userTransfer = $this->resolveUserTransferByMerchantTransfer($merchantTransfer);
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
        $this->updateUserByMerchant($merchantUserTransfer, $merchantTransfer);

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
    public function updateUserByMerchant(MerchantUserTransfer $merchantUserTransfer, MerchantTransfer $merchantTransfer): MerchantUserResponseTransfer
    {
        $merchantUserTransferByUser = $this->merchantUserRepository->findOne(
            (new MerchantUserCriteriaFilterTransfer())->setIdUser($merchantUserTransfer->getIdUser())->setIdMerchant($merchantTransfer->getIdMerchant())
        );

        if (!$merchantUserTransferByUser) {
            return (new MerchantUserResponseTransfer())
                ->setIsSuccess(false)
                ->addError((new MessageTransfer())->setMessage(static::MERCHANT_USER_NOT_FOUND_ERROR_MESSAGE));
        }

        $userTransfer = $this->userFacade->updateUser($this->fillUserTransferFromMerchantTransfer(
            $this->userFacade->getUserById($merchantUserTransfer->getIdUser()),
            $merchantTransfer
        ));

        return (new MerchantUserResponseTransfer())
            ->setIsSuccess(true)
            ->setMerchantUser($merchantUserTransferByUser->setUser($userTransfer));
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
    protected function resolveUserTransferByMerchantTransfer(MerchantTransfer $merchantTransfer): UserTransfer
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
        $userTransfer = $this->fillUserTransferFromMerchantTransfer(new UserTransfer(), $merchantTransfer)
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
    protected function fillUserTransferFromMerchantTransfer(UserTransfer $userTransfer, MerchantTransfer $merchantTransfer): UserTransfer
    {
        return $userTransfer
            ->setFirstName($merchantTransfer->getMerchantProfile()->getContactPersonFirstName())
            ->setLastName($merchantTransfer->getMerchantProfile()->getContactPersonLastName())
            ->setUsername($merchantTransfer->getEmail());
    }
}
