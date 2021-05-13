<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturn\Business\Validator;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Generated\Shared\Transfer\ReturnResponseTransfer;

class MerchantReturnValidator implements MerchantReturnValidatorInterface
{
    protected const ERROR_MESSAGE_MERCHANT_RETURN_ITEMS_FROM_DIFFERENT_MERCHANTS = 'merchant_sales_return.message.items_from_different_merchant_detected';

    /**
     * @param \Generated\Shared\Transfer\ReturnCreateRequestTransfer $returnCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    public function validate(ReturnCreateRequestTransfer $returnCreateRequestTransfer): ReturnResponseTransfer
    {
        $returnResponseTransfer = (new ReturnResponseTransfer())
            ->setIsSuccessful(true);

        $previousItemTransfer = null;
        foreach ($returnCreateRequestTransfer->getReturnItems() as $returnItemTransfer) {
            $itemTransfer = $returnItemTransfer->getOrderItemOrFail();

            if (
                $previousItemTransfer
                && $itemTransfer->getMerchantReference() !== $previousItemTransfer->getMerchantReference()
            ) {
                return $this->addErrorMessageToResponse(
                    static::ERROR_MESSAGE_MERCHANT_RETURN_ITEMS_FROM_DIFFERENT_MERCHANTS,
                    $returnResponseTransfer
                );
            }

            $previousItemTransfer = $itemTransfer;
        }

        return $returnResponseTransfer;
    }

    /**
     * @param string $message
     * @param \Generated\Shared\Transfer\ReturnResponseTransfer $returnResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    protected function addErrorMessageToResponse(
        string $message,
        ReturnResponseTransfer $returnResponseTransfer
    ): ReturnResponseTransfer {
        $messageTransfer = (new MessageTransfer())
            ->setValue($message);

        $returnResponseTransfer
            ->setIsSuccessful(false)
            ->addMessage($messageTransfer);

        return $returnResponseTransfer;
    }
}
