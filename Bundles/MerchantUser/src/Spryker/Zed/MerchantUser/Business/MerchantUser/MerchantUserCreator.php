<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\MerchantUser;

use ArrayObject;
use Generated\Shared\Transfer\MerchantErrorTransfer;
use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantUserResponseTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\MerchantUser\Business\User\UserReaderInterface;
use Spryker\Zed\MerchantUser\Business\User\UserWriterInterface;
use Spryker\Zed\MerchantUser\Dependency\Service\MerchantUserToUtilTextServiceInterface;
use Spryker\Zed\MerchantUser\MerchantUserConfig;
use Spryker\Zed\MerchantUser\Persistence\MerchantUserEntityManagerInterface;
use Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface;

class MerchantUserCreator implements MerchantUserCreatorInterface
{
    protected const USER_HAVE_ANOTHER_MERCHANT_ERROR_MESSAGE = 'A user with the same email is already connected to another merchant.';
    protected const USER_CREATION_DEFAULT_PASSWORD_LENGTH = 64;

    /**
     * @var \Spryker\Zed\MerchantUser\Business\User\UserWriterInterface
     */
    protected $userWriter;

    /**
     * @var \Spryker\Zed\MerchantUser\Dependency\Service\MerchantUserToUtilTextServiceInterface
     */
    protected $utilTextService;

    /**
     * @var \Spryker\Zed\MerchantUser\Business\User\UserReaderInterface
     */
    protected $userReader;

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
     * @param \Spryker\Zed\MerchantUser\Business\User\UserWriterInterface $userWriter
     * @param \Spryker\Zed\MerchantUser\Business\User\UserReaderInterface $userReader
     * @param \Spryker\Zed\MerchantUser\Dependency\Service\MerchantUserToUtilTextServiceInterface $utilTextService
     * @param \Spryker\Zed\MerchantUser\Persistence\MerchantUserEntityManagerInterface $merchantUserEntityManager
     * @param \Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface $merchantUserRepository
     * @param \Spryker\Zed\MerchantUser\MerchantUserConfig $merchantUserConfig
     */
    public function __construct(
        UserWriterInterface $userWriter,
        UserReaderInterface $userReader,
        MerchantUserToUtilTextServiceInterface $utilTextService,
        MerchantUserEntityManagerInterface $merchantUserEntityManager,
        MerchantUserRepositoryInterface $merchantUserRepository,
        MerchantUserConfig $merchantUserConfig
    ) {
        $this->userWriter = $userWriter;
        $this->utilTextService = $utilTextService;
        $this->userReader = $userReader;
        $this->merchantUserEntityManager = $merchantUserEntityManager;
        $this->merchantUserRepository = $merchantUserRepository;
        $this->merchantUserConfig = $merchantUserConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function handleMerchantPostCreate(MerchantTransfer $merchantTransfer): MerchantResponseTransfer
    {
        $merchantUserTransfer = (new MerchantUserTransfer())
            ->setMerchant($merchantTransfer)
            ->setUser($this->resolveUserTransferByMerchant($merchantTransfer));

        $merchantUserResponseTransfer = $this->create($merchantUserTransfer);

        return (new MerchantResponseTransfer())
            ->setIsSuccess($merchantUserResponseTransfer->getIsSuccessful())
            ->setMerchant($merchantTransfer)
            ->setErrors($this->convertMessageTransfersToMerchantErrorTransfers($merchantUserResponseTransfer->getErrors()));
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    protected function create(MerchantUserTransfer $merchantUserTransfer): MerchantUserResponseTransfer
    {
        $merchantUserTransfer->requireMerchant()->getMerchant()->requireEmail();
        $merchantUserTransfer->requireUser()->getUser()->requireIdUser();

        if (!$this->merchantUserConfig->canUserHaveManyMerchants()
            && $this->hasUserAnotherMerchant($merchantUserTransfer->getUser(), $merchantUserTransfer->getMerchant())
        ) {
            $merchantUserResponseTransfer = $this->createMerchantUserResponseTransfer($merchantUserTransfer);

            return $this->addMessageToMerchantUserResponseTransfer(
                $merchantUserResponseTransfer,
                sprintf(static::USER_HAVE_ANOTHER_MERCHANT_ERROR_MESSAGE, $merchantUserTransfer->getMerchant()->getEmail())
            );
        }

        $merchantUserTransfer = $this->merchantUserEntityManager->create(
            (new MerchantUserTransfer())
                ->setIdMerchant($merchantUserTransfer->getMerchant()->getIdMerchant())
                ->setIdUser($merchantUserTransfer->getUser()->getIdUser())
        );

        return $this->createMerchantUserResponseTransfer($merchantUserTransfer)
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserResponseTransfer $merchantUserResponseTransfer
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    protected function addMessageToMerchantUserResponseTransfer(
        MerchantUserResponseTransfer $merchantUserResponseTransfer,
        string $message
    ): MerchantUserResponseTransfer {
        $merchantUserResponseTransfer->addError((new MessageTransfer())->setMessage($message));

        return $merchantUserResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    protected function createMerchantUserResponseTransfer(MerchantUserTransfer $merchantUserTransfer): MerchantUserResponseTransfer
    {
        return (new MerchantUserResponseTransfer())
            ->setIsSuccessful(false)
            ->setMerchantUser($merchantUserTransfer);
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
        if (!$this->userReader->hasUserByMerchant($merchantTransfer)) {
            $userTransfer = $this->fillUserTransferFromMerchant(new UserTransfer(), $merchantTransfer)
                ->setPassword($this->utilTextService->generateRandomByteString(static::USER_CREATION_DEFAULT_PASSWORD_LENGTH))
                ->setStatus($this->merchantUserConfig->getUserCreationStatus());

            return $this->userWriter->createUser($userTransfer);
        }
        $userTransfer = $this->userReader->getUserByMerchant($merchantTransfer);

        return $this->userWriter->updateUser($this->fillUserTransferFromMerchant($userTransfer, $merchantTransfer));
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

    /**
     * @param \ArrayObject $messageTransfers
     *
     * @return \ArrayObject
     */
    protected function convertMessageTransfersToMerchantErrorTransfers(ArrayObject $messageTransfers): ArrayObject
    {
        $result = new ArrayObject();
        /** @var \Generated\Shared\Transfer\MessageTransfer $messageTransfer */
        foreach ($messageTransfers as $messageTransfer) {
            $result[] = (new MerchantErrorTransfer())->setMessage($messageTransfer->getMessage());
        }

        return $result;
    }
}
