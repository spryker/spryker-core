<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\Merchant;

use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserCriteriaFilterTransfer;
use Spryker\Zed\MerchantUser\Business\MerchantUser\MerchantUserWriterInterface;
use Spryker\Zed\MerchantUser\Business\Message\MessageConverterInterface;
use Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface;

class MerchantPostUpdater implements MerchantPostUpdaterInterface
{
    /**
     * @var \Spryker\Zed\MerchantUser\Business\MerchantUser\MerchantUserWriterInterface
     */
    protected $merchantUserWriter;

    /**
     * @var \Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface
     */
    protected $merchantUserRepository;

    /**
     * @var \Spryker\Zed\MerchantUser\Business\Merchant\MerchantPostCreatorInterface
     */
    protected $merchantPostCreator;

    /**
     * @var \Spryker\Zed\MerchantUser\Business\Message\MessageConverterInterface
     */
    protected $messageConverter;

    /**
     * @param \Spryker\Zed\MerchantUser\Business\MerchantUser\MerchantUserWriterInterface $merchantUserWriter
     * @param \Spryker\Zed\MerchantUser\Business\Merchant\MerchantPostCreatorInterface $merchantPostCreator
     * @param \Spryker\Zed\MerchantUser\Business\Message\MessageConverterInterface $messageConverter
     * @param \Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface $merchantUserRepository
     */
    public function __construct(
        MerchantUserWriterInterface $merchantUserWriter,
        MerchantPostCreatorInterface $merchantPostCreator,
        MessageConverterInterface $messageConverter,
        MerchantUserRepositoryInterface $merchantUserRepository
    ) {
        $this->merchantUserWriter = $merchantUserWriter;
        $this->merchantUserRepository = $merchantUserRepository;
        $this->merchantPostCreator = $merchantPostCreator;
        $this->messageConverter = $messageConverter;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $originalMerchantTransfer
     * @param \Generated\Shared\Transfer\MerchantTransfer $updatedMerchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function handleMerchantPostUpdate(MerchantTransfer $originalMerchantTransfer, MerchantTransfer $updatedMerchantTransfer): MerchantResponseTransfer
    {
        $merchantUserTransfer = $this->merchantUserRepository->findOne(
            (new MerchantUserCriteriaFilterTransfer())->setIdMerchant($updatedMerchantTransfer->getIdMerchant())
        );
        if (!$merchantUserTransfer) {
            return $this->merchantPostCreator->handleMerchantPostCreate($updatedMerchantTransfer);
        }

        $merchantUserResponseTransfer = $this->merchantUserWriter->syncUserWithMerchant(
            $originalMerchantTransfer,
            $updatedMerchantTransfer,
            $merchantUserTransfer
        );

        return (new MerchantResponseTransfer())
            ->setIsSuccess($merchantUserResponseTransfer->getIsSuccess())
            ->setMerchant($updatedMerchantTransfer)
            ->setErrors($this->messageConverter->convertMessageTransfersToMerchantErrorTransfers($merchantUserResponseTransfer->getErrors()));
    }
}
