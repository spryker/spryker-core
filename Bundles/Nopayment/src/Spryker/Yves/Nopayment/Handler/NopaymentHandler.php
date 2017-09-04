<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Nopayment\Handler;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Nopayment\NopaymentConstants;

class NopaymentHandler
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addPaymentToQuote(QuoteTransfer $quoteTransfer)
    {
        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer->setPaymentSelection(NopaymentConstants::PAYMENT_PROVIDER_NAME);
        $paymentTransfer->setPaymentProvider(NopaymentConstants::PAYMENT_PROVIDER_NAME);
        $paymentTransfer->setPaymentMethod(NopaymentConstants::PAYMENT_METHOD_NAME);
        $paymentTransfer->setIsLimitedAmount(true);
        $paymentTransfer->setAmount(0);

        $quoteTransfer->setPayment($paymentTransfer);

        return $quoteTransfer;
    }

}
