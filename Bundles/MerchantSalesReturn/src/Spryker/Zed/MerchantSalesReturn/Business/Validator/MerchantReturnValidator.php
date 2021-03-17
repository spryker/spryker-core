<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturn\Business\Validator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Generated\Shared\Transfer\ReturnResponseTransfer;

class MerchantReturnValidator implements MerchantReturnValidatorInterface
{
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
            $itemTransfer = $returnItemTransfer->getOrderItem();

            if (
                $previousItemTransfer
                && !$this->isItemFromTheSameMerchantOrder($itemTransfer, $previousItemTransfer)
            ) {
                return $this->addErrorMessageToResponse(
                    'merchant_sales_return.message.items_from_different_merchant_detected',
                    $returnResponseTransfer
                );
            }

            $previousItemTransfer = $itemTransfer;
        }

        return $returnResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransferToCompare
     *
     * @return bool
     */
    protected function isItemFromTheSameMerchantOrder(
        ItemTransfer $itemTransfer,
        ItemTransfer $itemTransferToCompare
    ): bool {
        return $itemTransfer->getMerchantReference() === $itemTransferToCompare->getMerchantReference()
            && $itemTransfer->getFkSalesOrderOrFail() === $itemTransferToCompare->getFkSalesOrderOrFail();
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
