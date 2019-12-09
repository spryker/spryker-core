<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\CartCode;

use Generated\Shared\Transfer\QuoteTransfer;

class GiftCardCartCodeRemover implements GiftCardCartCodeRemoverInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $cartCode
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeCartCode(QuoteTransfer $quoteTransfer, string $cartCode): QuoteTransfer
    {
        $quoteTransfer = $this->removeGiftCard($quoteTransfer, $cartCode);

        return $this->removeGiftCardPayment($quoteTransfer, $cartCode);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function removeGiftCard(QuoteTransfer $quoteTransfer, $code): QuoteTransfer
    {
        $giftCardTransferCollection = $quoteTransfer->getGiftCards();

        foreach ($giftCardTransferCollection as $index => $giftCardTransfer) {
            if ($giftCardTransfer->getCode() === $code) {
                $giftCardTransferCollection->offsetUnset($index);
            }
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string|null $code
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function removeGiftCardPayment(QuoteTransfer $quoteTransfer, ?string $code = null): QuoteTransfer
    {
        foreach ($quoteTransfer->getPayments() as $index => $payment) {
            if (!$payment->getGiftCard()) {
                return $quoteTransfer;
            }

            if ($code === null || $payment->getGiftCard()->getCode() === $code) {
                $quoteTransfer->getPayments()->offsetUnset($index);
            }
        }

        $quoteTransfer->setPayment(null);

        return $quoteTransfer;
    }
}
