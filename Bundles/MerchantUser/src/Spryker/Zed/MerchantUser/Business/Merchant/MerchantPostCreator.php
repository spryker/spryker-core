<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\Merchant;

use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\MerchantUser\Business\MerchantUser\MerchantUserWriterInterface;
use Spryker\Zed\MerchantUser\Business\Message\MessageConverterInterface;

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
     * @param \Spryker\Zed\MerchantUser\Business\MerchantUser\MerchantUserWriterInterface $merchantUserWriter
     * @param \Spryker\Zed\MerchantUser\Business\Message\MessageConverterInterface $messageConverter
     */
    public function __construct(
        MerchantUserWriterInterface $merchantUserWriter,
        MessageConverterInterface $messageConverter
    ) {
        $this->merchantUserWriter = $merchantUserWriter;
        $this->messageConverter = $messageConverter;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function handleMerchantPostCreate(MerchantTransfer $merchantTransfer): MerchantResponseTransfer
    {
        $merchantUserResponseTransfer = $this->merchantUserWriter->createByMerchant($merchantTransfer);

        return (new MerchantResponseTransfer())
            ->setIsSuccess($merchantUserResponseTransfer->getIsSuccess())
            ->setMerchant($merchantTransfer)
            ->setErrors($this->messageConverter->convertMessageTransfersToMerchantErrorTransfers($merchantUserResponseTransfer->getErrors()));
    }
}
