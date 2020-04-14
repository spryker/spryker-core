<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\Availability\PreCheck;

use ArrayObject;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
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
        $checkoutErrorMessages = $this->getCheckoutAvailabilityFailedItemsErrorMessages($quoteTransfer);

        if ($checkoutErrorMessages->count() === 0) {
            return true;
        }

        $checkoutResponseTransfer
            ->setIsSuccess(false)
            ->setErrors($checkoutErrorMessages);

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\CheckoutErrorTransfer[]
     */
    protected function getCheckoutAvailabilityFailedItemsErrorMessages(QuoteTransfer $quoteTransfer): ArrayObject
    {
        $checkoutErrorMessages = new ArrayObject();
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getAmountLeadProduct() || !$itemTransfer->getAmount() || $itemTransfer->getAmount()->lessThan(0)) {
                continue;
            }

            $checkoutErrorMessages = $this->collectCheckoutErrorMessages(
                $checkoutErrorMessages,
                $this->checkPackagingUnitAvailability($itemTransfer, $quoteTransfer)
            );

            $checkoutErrorMessages = $this->collectCheckoutErrorMessages(
                $checkoutErrorMessages,
                $this->checkPackagingUnitLeadProductAvailability($itemTransfer, $quoteTransfer)
            );
        }

        return $checkoutErrorMessages;
    }

    /**
     * Skip if self-lead PU.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer|null
     */
    protected function checkPackagingUnitAvailability(
        ItemTransfer $itemTransfer,
        QuoteTransfer $quoteTransfer
    ): ?CheckoutErrorTransfer {
        $isPackagingUnitSellable = $this->isPackagingUnitSellable(
            $itemTransfer,
            $quoteTransfer->getStore()
        );

        if ($isPackagingUnitSellable) {
            return null;
        }

        return $this->createCheckoutResponseTransfer(
            static::CHECKOUT_PRODUCT_UNAVAILABLE_TRANSLATION_KEY
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer|null
     */
    protected function checkPackagingUnitLeadProductAvailability(
        ItemTransfer $itemTransfer,
        QuoteTransfer $quoteTransfer
    ): ?CheckoutErrorTransfer {
        $isPackagingUnitLeadProductSellable = $this->isPackagingUnitLeadProductSellable(
            $itemTransfer,
            clone $quoteTransfer->getItems(),
            $quoteTransfer->getStore()
        );

        if ($isPackagingUnitLeadProductSellable) {
            return null;
        }

        return $this->createCheckoutResponseTransfer(
            static::CHECKOUT_PRODUCT_UNAVAILABLE_TRANSLATION_KEY
        );
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\CheckoutErrorTransfer[] $checkoutErrorMessages
     * @param \Generated\Shared\Transfer\CheckoutErrorTransfer|null $messageTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\CheckoutErrorTransfer[]
     */
    protected function collectCheckoutErrorMessages(
        ArrayObject $checkoutErrorMessages,
        ?CheckoutErrorTransfer $messageTransfer
    ): ArrayObject {
        if ($messageTransfer !== null) {
            $checkoutErrorMessages->append($checkoutErrorMessages);
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
        return (new CheckoutErrorTransfer())->setMessage($message);
    }
}
