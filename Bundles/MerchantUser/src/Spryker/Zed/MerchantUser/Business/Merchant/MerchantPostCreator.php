<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\Merchant;

use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserResponseTransfer;
use Spryker\Zed\MerchantUser\Business\Group\GroupAdderInterface;
use Spryker\Zed\MerchantUser\Business\MerchantUser\MerchantUserWriterInterface;
use Spryker\Zed\MerchantUser\Business\Message\MessageConverterInterface;
use Spryker\Zed\MerchantUser\MerchantUserConfig;

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
     * @var \Spryker\Zed\MerchantUser\Business\Group\GroupAdderInterface
     */
    protected $groupAdder;

    /**
     * @param \Spryker\Zed\MerchantUser\Business\MerchantUser\MerchantUserWriterInterface $merchantUserWriter
     * @param \Spryker\Zed\MerchantUser\Business\Message\MessageConverterInterface $messageConverter
     * @param \Spryker\Zed\MerchantUser\Business\Group\GroupAdderInterface $groupAdder
     */
    public function __construct(
        MerchantUserWriterInterface $merchantUserWriter,
        MessageConverterInterface $messageConverter,
        GroupAdderInterface $groupAdder
    ) {
        $this->merchantUserWriter = $merchantUserWriter;
        $this->messageConverter = $messageConverter;
        $this->groupAdder = $groupAdder;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function handleMerchantPostCreate(MerchantTransfer $merchantTransfer): MerchantResponseTransfer
    {
        $merchantResponseTransfer = (new MerchantResponseTransfer())->setIsSuccess(true)->setMerchant($merchantTransfer);
        $merchantUserResponseTransfer = $this->merchantUserWriter->createByMerchant($merchantTransfer);

        if (!$merchantUserResponseTransfer->getIsSuccess()) {
            return $this->addMerchantUserResponseErrorsToMerchantResponse($merchantUserResponseTransfer, $merchantResponseTransfer);
        }

        $merchantUserResponseTransfer = $this->groupAdder->addUserToGroupByReference(
            $merchantUserResponseTransfer->getMerchantUser(),
            MerchantUserConfig::MERCHANT_PORTAL_ADMIN_GROUP_REFERENCE
        );

        if (!$merchantUserResponseTransfer->getIsSuccess()) {
            return $this->addMerchantUserResponseErrorsToMerchantResponse($merchantUserResponseTransfer, $merchantResponseTransfer);
        }

        return $merchantResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserResponseTransfer $merchantUserResponseTransfer
     * @param \Generated\Shared\Transfer\MerchantResponseTransfer $merchantResponseTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    protected function addMerchantUserResponseErrorsToMerchantResponse(
        MerchantUserResponseTransfer $merchantUserResponseTransfer,
        MerchantResponseTransfer $merchantResponseTransfer
    ): MerchantResponseTransfer {
        return $merchantResponseTransfer
            ->setIsSuccess($merchantUserResponseTransfer->getIsSuccess())
            ->setErrors($this->messageConverter->convertMessageTransfersToMerchantErrorTransfers($merchantUserResponseTransfer->getErrors()));
    }
}
