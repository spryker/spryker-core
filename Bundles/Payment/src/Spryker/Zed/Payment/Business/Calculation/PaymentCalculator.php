<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Calculation;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\TotalsTransfer;

class PaymentCalculator implements PaymentCalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculatePayments(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $paymentCollection = $this->getPaymentCollection($calculableObjectTransfer);

        $this->definePriceToPay(
            $calculableObjectTransfer->getTotals()
        );

        $this->applyLimitedAmountPayments(
            $calculableObjectTransfer->getTotals(),
            $paymentCollection
        );

        $this->applyUnlimitedAmountPayments(
            $calculableObjectTransfer->getTotals(),
            $paymentCollection
        );
    }

    /**
     * @deprecated To be removed when the single payment property
     * Use \Generated\Shared\Transfer\CalculableObjectTransfer::getPayments directly
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer[]
     */
    protected function getPaymentCollection(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $result = [];

        foreach ($calculableObjectTransfer->getPayments() as $payment) {
            $result[] = $payment;
        }

        $singlePayment = $calculableObjectTransfer->getPayment();

        if ($singlePayment) {
            $result[] = $singlePayment;
        }

        return $result;
    }

    /**
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     *
     * @return void
     */
    protected function definePriceToPay(TotalsTransfer $totalsTransfer)
    {
        $totalsTransfer->setPriceToPay(
            $totalsTransfer->getGrandTotal()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     * @param \Generated\Shared\Transfer\PaymentTransfer[] $paymentTransfers
     *
     * @return void
     */
    protected function applyLimitedAmountPayments(TotalsTransfer $totalsTransfer, array $paymentTransfers)
    {
        $priceToPay = $totalsTransfer->getPriceToPay();

        foreach ($paymentTransfers as $paymentTransfer) {
            if (!$paymentTransfer->getIsLimitedAmount()) {
                continue;
            }

            if ($paymentTransfer->getAvailableAmount() >= $priceToPay) {
                $paymentTransfer->setAmount($priceToPay);
                $priceToPay = 0;
            } else {
                $paymentTransfer->setAmount(
                    $paymentTransfer->getAvailableAmount()
                );

                $priceToPay = $priceToPay - $paymentTransfer->getAvailableAmount();
            }
        }

        $totalsTransfer->setPriceToPay($priceToPay);
    }

    /**
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     * @param \Generated\Shared\Transfer\PaymentTransfer[] $paymentTransfers
     *
     * @return void
     */
    protected function applyUnlimitedAmountPayments(TotalsTransfer $totalsTransfer, array $paymentTransfers)
    {
        foreach ($paymentTransfers as $paymentTransfer) {
            if ($paymentTransfer->getIsLimitedAmount()) {
                continue;
            }

            $paymentTransfer->setAmount(
                $totalsTransfer->getPriceToPay()
            );
        }
    }
}
