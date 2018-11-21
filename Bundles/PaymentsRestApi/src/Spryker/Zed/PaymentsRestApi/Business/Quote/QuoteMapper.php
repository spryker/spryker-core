<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentsRestApi\Business\Quote;

use ArrayObject;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestPaymentTransfer;

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
        $restPaymentTransfers = $restCheckoutRequestAttributesTransfer->getCart()->getPayments();
        $quoteTransfer = $this->setFirstPaymentMethodWithUnlimitedAmountToQuote($restPaymentTransfers, $quoteTransfer);

        foreach ($restPaymentTransfers as $restPaymentTransfer) {
            if ($quoteTransfer->getPayment()->getPaymentSelection() !== $restPaymentTransfer->getPaymentSelection()) {
                $quoteTransfer->addPayment($this->preparePaymentTransfer($restPaymentTransfer));
            }
        }

        return $quoteTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\RestPaymentTransfer[] $restPaymentTransfers
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setFirstPaymentMethodWithUnlimitedAmountToQuote(
        ArrayObject $restPaymentTransfers,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        foreach ($restPaymentTransfers as $restPaymentTransfer) {
            if (!$restPaymentTransfer->getIsLimitedAmount()) {
                $quoteTransfer->setPayment($this->preparePaymentTransfer($restPaymentTransfer));

                return $quoteTransfer;
            }
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestPaymentTransfer $restPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    protected function preparePaymentTransfer(RestPaymentTransfer $restPaymentTransfer): PaymentTransfer
    {
        return (new PaymentTransfer())->fromArray($restPaymentTransfer->toArray(), true);
    }
}
