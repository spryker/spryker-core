<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Ratepay\Business\Order;

use Generated\Shared\Transfer\RatepayPaymentInvoiceTransfer;
use Spryker\Shared\Ratepay\RatepayConstants;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Ratepay
 * @group Business
 * @group Order
 * @group InvoiceSaverTest
 * Add your own group annotations below this line
 */
class InvoiceSaverTest extends AbstractSaverTest
{
    /**
     * @const Payment method code.
     */
    public const PAYMENT_METHOD = RatepayConstants::INVOICE;

    /**
     * @return \Generated\Shared\Transfer\RatepayPaymentInvoiceTransfer
     */
    protected function getRatepayPaymentMethodTransfer()
    {
        return new RatepayPaymentInvoiceTransfer();
    }

    /**
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function getPaymentTransferFromQuote()
    {
        return $this->quoteTransfer->getPayment()->getRatepayInvoice();
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $payment
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $paymentTransfer
     *
     * @return void
     */
    protected function setRatepayPaymentDataToPaymentTransfer($payment, $paymentTransfer)
    {
        $payment->setRatepayInvoice($paymentTransfer);
    }
}
