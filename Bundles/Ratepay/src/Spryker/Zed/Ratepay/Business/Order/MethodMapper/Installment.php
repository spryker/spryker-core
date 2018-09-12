<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Order\MethodMapper;

use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay;
use Spryker\Shared\Ratepay\RatepayConstants;

class Installment extends AbstractMapper
{
    /**
     * @return string
     */
    public function getMethodName()
    {
        return RatepayConstants::INSTALLMENT;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer
     */
    protected function getPaymentTransfer(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->getPayment()->getRatepayInstallment();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay $payment
     *
     * @return void
     */
    public function mapMethodDataToPayment(QuoteTransfer $quoteTransfer, SpyPaymentRatepay $payment)
    {
        parent::mapMethodDataToPayment($quoteTransfer, $payment);

        $paymentTransfer = $this->getPaymentTransfer($quoteTransfer);
        $payment
            ->setBankAccountBic($paymentTransfer->getBankAccountBic())
            ->setBankAccountIban($paymentTransfer->getBankAccountIban())

            ->setDebitPayType($paymentTransfer->getDebitPayType())

            ->setInstallmentTotalAmount($paymentTransfer->getInstallmentGrandTotalAmount())
            ->setInstallmentInterestAmount($paymentTransfer->getInstallmentInterestAmount())
            ->setInstallmentInterestRate($paymentTransfer->getInstallmentInterestRate())
            ->setInstallmentLastRate($paymentTransfer->getInstallmentLastRate())
            ->setInstallmentRate($paymentTransfer->getInstallmentRate())
            ->setInstallmentPaymentFirstDay($paymentTransfer->getInstallmentPaymentFirstDay())
            ->setInstallmentMonth($paymentTransfer->getInstallmentMonth())
            ->setInstallmentNumberRates($paymentTransfer->getInstallmentNumberRates())
            ->setInstallmentCalculationStart($paymentTransfer->getInstallmentCalculationStart())
            ->setInstallmentServiceCharge($paymentTransfer->getInstallmentServiceCharge())
            ->setInstallmentAnnualPercentageRate($paymentTransfer->getInstallmentAnnualPercentageRate())
            ->setInstallmentMonthAllowed($paymentTransfer->getInstallmentMonthAllowed());
    }
}
