<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturn\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Generated\Shared\Transfer\ReturnResponseTransfer;

class MerchantReturnValidator implements MerchantReturnValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReturnCreateRequestTransfer $returnCreateRequestTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    public function validate(
        ReturnCreateRequestTransfer $returnCreateRequestTransfer,
        ArrayObject $itemTransfers
    ): ReturnResponseTransfer {
        $returnResponseTransfer = (new ReturnResponseTransfer())
            ->setIsSuccessful(true);

        $previousIdSalesOrder = null;
        $previousMerchantReference = null;
        foreach ($itemTransfers as $itemTransfer) {
            if ($previousMerchantReference && $previousIdSalesOrder) {
                if (
                    $this->areMerchantReferencesDifferent($itemTransfer, $previousMerchantReference)
                    || $this->areSalesOrderIdsDifferent($itemTransfer, $previousIdSalesOrder)
                ) {
                    return $this->addErrorMessageToResponse(
                        'merchant_sales_return.message.items_from_different_merchant_detected',
                        $returnResponseTransfer
                    );
                }
            }

            $previousIdSalesOrder = $itemTransfer->getFkSalesOrderOrFail();
            $previousMerchantReference = $itemTransfer->getMerchantReferenceOrFail();
        }

        return $returnResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $merchantReference
     *
     * @return bool
     */
    protected function areMerchantReferencesDifferent(ItemTransfer $itemTransfer, string $merchantReference): bool
    {
        return $itemTransfer->getMerchantReference() !== $merchantReference;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $idSalesOrder
     *
     * @return bool
     */
    protected function areSalesOrderIdsDifferent(ItemTransfer $itemTransfer, int $idSalesOrder): bool
    {
        return $itemTransfer->getFkSalesOrder() !== $idSalesOrder;
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
            ->setMessage($message);

        $returnResponseTransfer
            ->setIsSuccessful(false)
            ->addMessage($messageTransfer);

        return $returnResponseTransfer;
    }
}
