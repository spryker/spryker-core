<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\Merchant;

use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\MerchantUser\Business\MerchantUser\MerchantUserWriterInterface;
use Spryker\Zed\MerchantUser\Business\Message\MessageConverterInterface;
use Spryker\Zed\MerchantUser\Business\User\UserWriterInterface;
use Spryker\Zed\MerchantUser\Dependency\Service\MerchantUserToUtilTextServiceInterface;
use Spryker\Zed\MerchantUser\MerchantUserConfig;
use Spryker\Zed\User\Business\Exception\UserNotFoundException;

class MerchantPostCreator implements MerchantPostCreatorInterface
{
    /**
     * @var \Spryker\Zed\MerchantUser\Business\MerchantUser\MerchantUserWriterInterface
     */
    protected $merchantUserWriter;

    /**
     * @var \Spryker\Zed\MerchantUser\Business\Message\MessageConverterInterface
     */
    protected $messageConverter;

    /**
     * @var \Spryker\Zed\MerchantUser\Business\User\UserWriterInterface
     */
    protected $userWriter;

    /**
     * @var \Spryker\Zed\MerchantUser\Dependency\Service\MerchantUserToUtilTextServiceInterface
     */
    private $utilTextService;

    /**
     * @param \Spryker\Zed\MerchantUser\Business\MerchantUser\MerchantUserWriterInterface $merchantUserWriter
     * @param \Spryker\Zed\MerchantUser\Business\Message\MessageConverterInterface $messageConverter
     * @param \Spryker\Zed\MerchantUser\Business\User\UserWriterInterface $userWriter
     * @param \Spryker\Zed\MerchantUser\Dependency\Service\MerchantUserToUtilTextServiceInterface $utilTextService
     */
    public function __construct(
        MerchantUserWriterInterface $merchantUserWriter,
        MessageConverterInterface $messageConverter,
        UserWriterInterface $userWriter,
        MerchantUserToUtilTextServiceInterface $utilTextService
    ) {
        $this->merchantUserWriter = $merchantUserWriter;
        $this->messageConverter = $messageConverter;
        $this->userWriter = $userWriter;
        $this->utilTextService = $utilTextService;
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

        $merchantUserResponseTransfer = $this->merchantUserWriter->create($merchantUserTransfer);

        return (new MerchantResponseTransfer())
            ->setIsSuccess($merchantUserResponseTransfer->getIsSuccess())
            ->setMerchant($merchantTransfer)
            ->setErrors($this->messageConverter->convertMessageTransfersToMerchantErrorTransfers($merchantUserResponseTransfer->getErrors()));
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function resolveUserTransferByMerchant(MerchantTransfer $merchantTransfer): UserTransfer
    {
        try {
            $userTransfer = $this->userWriter->getUserByMerchant($merchantTransfer);

            return $this->userWriter->updateUser($this->fillUserTransferFromMerchant($userTransfer, $merchantTransfer));
        } catch (UserNotFoundException $exception) {
            $userTransfer = $this->fillUserTransferFromMerchant(new UserTransfer(), $merchantTransfer)
                ->setPassword($this->utilTextService->generateRandomString(MerchantUserConfig::USER_CREATION_DEFAULT_PASSWORD_LENGTH))
                ->setStatus(MerchantUserConfig::USER_CREATION_DEFAULT_STATUS);

            return $this->userWriter->createUser($userTransfer);
        }
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
