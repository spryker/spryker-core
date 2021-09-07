<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Nopayment\Business\Checkout;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Nopayment\NopaymentConfig;

class NopaymentCheckoutPreConditionChecker implements NopaymentCheckoutPreConditionCheckerInterface
{
    /**
     * @var int
     */
    protected const ERROR_CODE_NOPAYMENT_NOT_ALLOWED = 403;

    /**
     * {@inheritDoc}
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkCondition(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        if ($this->hasNopaymentPayments($quoteTransfer) && $quoteTransfer->getTotals()->getPriceToPay() > 0) {
            $checkoutResponseTransfer->addError((new CheckoutErrorTransfer())
                ->setMessage('Nopayment is only available if the price to pay is 0')
                ->setErrorCode(static::ERROR_CODE_NOPAYMENT_NOT_ALLOWED));

            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function hasNopaymentPayments(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getPayments() as $payment) {
            if ($payment->getPaymentProvider() === NopaymentConfig::PAYMENT_PROVIDER_NAME) {
                return true;
            }
        }

        return false;
    }
}
