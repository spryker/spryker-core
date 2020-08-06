<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSwitcher\Business\MerchantInQuoteValidator;

use ArrayObject;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\SingleMerchantQuoteValidationRequestTransfer;
use Generated\Shared\Transfer\SingleMerchantQuoteValidationResponseTransfer;

class MerchantInQuoteValidator implements MerchantInQuoteValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\SingleMerchantQuoteValidationRequestTransfer $singleMerchantQuoteValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SingleMerchantQuoteValidationResponseTransfer
     */
    public function validateMerchantInQuoteItems(
        SingleMerchantQuoteValidationRequestTransfer $singleMerchantQuoteValidationRequestTransfer
    ): SingleMerchantQuoteValidationResponseTransfer {
        if (!$singleMerchantQuoteValidationRequestTransfer->getMerchantReference()) {
            return (new SingleMerchantQuoteValidationResponseTransfer())
                ->setIsSuccessful(true);
        }

        $messageTransfers = [];
        foreach ($singleMerchantQuoteValidationRequestTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getMerchantReference() && $itemTransfer->getMerchantReference() !== $singleMerchantQuoteValidationRequestTransfer->getMerchantReference()) {
                $messageTransfers[] = (new MessageTransfer())
                    ->setValue('merchant_switcher.message.product_is_not_available')
                    ->setParameters([
                        '%product_name%' => $itemTransfer->getName(),
                        '%sku%' => $itemTransfer->getSku(),
                    ]);
            }
        }

        return (new SingleMerchantQuoteValidationResponseTransfer())
            ->setIsSuccessful(!$messageTransfers)
            ->setErrors(new ArrayObject($messageTransfers));
    }
}
