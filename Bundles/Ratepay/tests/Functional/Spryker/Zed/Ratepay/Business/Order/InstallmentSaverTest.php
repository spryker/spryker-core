<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Ratepay\Business\Order;

use Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer;
use Spryker\Shared\Ratepay\RatepayConstants;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Ratepay
 * @group Business
 * @group Order
 * @group InstallmentSaverTest
 */
class InstallmentSaverTest extends AbstractSaverTest
{

    /**
     * @const Payment method code.
     */
    const PAYMENT_METHOD = RatepayConstants::INSTALLMENT;

    /**
     * @return \Generated\Shared\Transfer\RatepayPaymentInvoiceTransfer
     */
    protected function getRatepayPaymentMethodTransfer()
    {
        return new RatepayPaymentInstallmentTransfer();
    }

    /**
     * @return \Spryker\Shared\Transfer\AbstractTransfer
     */
    protected function getPaymentTransferFromQuote()
    {
        return $this->quoteTransfer->getPayment()->getRatepayInstallment();
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $payment
     * @param \Spryker\Shared\Transfer\TransferInterface $paymentTransfer
     *
     * @return void
     */
    protected function setRatepayPaymentDataToPaymentTransfer($payment, $paymentTransfer)
    {
        $payment->setRatepayInstallment($paymentTransfer);
    }

}
