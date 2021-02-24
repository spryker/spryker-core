<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturn\Business\Model;

use ArrayObject;
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

        $currentMerchantReference = null;
        foreach ($itemTransfers as $itemTransfer) {
            if ($currentMerchantReference && $itemTransfer->getMerchantReference() !== $currentMerchantReference) {
                return $this->addErrorMessageToResponse(
                    'merchant_sales_return.message.items_from_different_merchant_detected',
                    $returnResponseTransfer
                );
            }

            $currentMerchantReference = $itemTransfer->getMerchantReferenceOrFail();
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
            ->setMessage($message);

        $returnResponseTransfer
            ->setIsSuccessful(false)
            ->addMessage($messageTransfer);

        return $returnResponseTransfer;
    }
}
