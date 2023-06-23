<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Nopayment\Business\Updater;

use Generated\Shared\Transfer\CartCodeRequestTransfer;
use Generated\Shared\Transfer\CartCodeResponseTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Nopayment\NopaymentConfig;

class QuotePaymentUpdater implements QuotePaymentUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartCodeRequestTransfer $cartCodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeResponseTransfer
     */
    public function update(CartCodeRequestTransfer $cartCodeRequestTransfer): CartCodeResponseTransfer
    {
        $quoteTransfer = $cartCodeRequestTransfer->getQuoteOrFail();
        $cartCodeResponseTransfer = (new CartCodeResponseTransfer())
            ->setQuote($quoteTransfer)
            ->setIsSuccessful(true);

        if (!$this->isPaid($quoteTransfer)) {
            return $cartCodeResponseTransfer;
        }

        $paymentTransfer = $this->createPaymentTransfer();
        $quoteTransfer->setPayment($paymentTransfer);

        return $cartCodeResponseTransfer->setQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isPaid(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getTotals() && $quoteTransfer->getTotalsOrFail()->getPriceToPay() === 0;
    }

    /**
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    protected function createPaymentTransfer(): PaymentTransfer
    {
        return (new PaymentTransfer())
            ->setPaymentSelection(NopaymentConfig::PAYMENT_PROVIDER_NAME)
            ->setPaymentProvider(NopaymentConfig::PAYMENT_PROVIDER_NAME)
            ->setPaymentMethod(NopaymentConfig::PAYMENT_METHOD_NAME)
            ->setIsLimitedAmount(true)
            ->setAmount(0);
    }
}
