<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\Availability\PreCheck;

use ArrayObject;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class ProductPackagingUnitCheckoutPreCheck extends ProductPackagingUnitAvailabilityPreCheck implements ProductPackagingUnitCheckoutPreCheckInterface
{
    public const CHECKOUT_PRODUCT_UNAVAILABLE_TRANSLATION_KEY = 'product.unavailable';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkoutAvailabilityPreCheck(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): bool {
        $checkoutErrorMessages = $this->getCheckoutAvailabilityFailedItems($quoteTransfer);

        if (count($checkoutErrorMessages) === 0) {
            return true;
        }

        $checkoutResponseTransfer->setIsSuccess(false);
        foreach ($checkoutErrorMessages as $checkoutErrorTransfer) {
            $checkoutResponseTransfer->addError($checkoutErrorTransfer);
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \ArrayObject
     */
    protected function getCheckoutAvailabilityFailedItems(QuoteTransfer $quoteTransfer): ArrayObject
    {
        $checkoutErrorMessages = new ArrayObject();

        $storeTransfer = $quoteTransfer->getStore();
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getAmountLeadProduct() || !$itemTransfer->getAmount()) {
                continue;
            }

            $isPackagingUnitLeadProductSellable = $this->isPackagingUnitLeadProductSellable(
                $itemTransfer,
                $quoteTransfer->getItems(),
                $storeTransfer
            );

            if ($itemTransfer->getAmount()->greaterThan(0) && !$isPackagingUnitLeadProductSellable) {
                $checkoutErrorMessages[] = $this->createCheckoutResponseTransfer(
                    static::CHECKOUT_PRODUCT_UNAVAILABLE_TRANSLATION_KEY
                );
            }
        }

        return $checkoutErrorMessages;
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer
     */
    protected function createCheckoutResponseTransfer(string $message): CheckoutErrorTransfer
    {
        return (new CheckoutErrorTransfer())
            ->setMessage($message);
    }
}
