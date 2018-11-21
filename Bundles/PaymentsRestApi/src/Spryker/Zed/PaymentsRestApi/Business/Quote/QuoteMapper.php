<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentsRestApi\Business\Quote;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;

class QuoteMapper implements QuoteMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapPaymentsToQuote(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        $payments = $restCheckoutRequestAttributesTransfer->getCart()->getPayments();
        $quoteTransfer = $this->setFirstPaymentMethodWithUnlimitedAmountToQuote($payments, $quoteTransfer);

        foreach ($payments as $paymentTransfer) {
            if ($quoteTransfer->getPayment()->getPaymentSelection() !== $paymentTransfer->getPaymentSelection()) {
                $quoteTransfer->addPayment($paymentTransfer);
            }
        }

        return $quoteTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\PaymentTransfer[] $payments
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setFirstPaymentMethodWithUnlimitedAmountToQuote(
        ArrayObject $payments,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        foreach ($payments as $paymentTransfer) {
            if (!$paymentTransfer->getIsLimitedAmount()) {
                $quoteTransfer->setPayment($paymentTransfer);

                return $quoteTransfer;
            }
        }

        return $quoteTransfer;
    }
}
