<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Calculation;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;

class PaymentCalculator
{

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculatePayments(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $paymentCollection = $this->getPaymentCollection($calculableObjectTransfer);
        $availablePriceToPay = $calculableObjectTransfer->getTotals()->getGrandTotal();

        //TODO build a new list with payments when the single payment property is removed
        //this is inplace mutation, which is not obvious
        foreach ($paymentCollection as $paymentTransfer) {
            if ($paymentTransfer->getAmount() > $availablePriceToPay || !$paymentTransfer->getIsLimitedAmount()) {
                $paymentTransfer->setAmount($availablePriceToPay);
                $availablePriceToPay = 0;
                continue;
            }

            $availablePriceToPay -= $paymentTransfer->getAmount();
        }

        //TODO one could remove payments without an amount here (but this might be easier without the extra payment property)

        $calculableObjectTransfer->getTotals()->setPriceToPay($availablePriceToPay);
    }

    /**
     * @deprecated To be removed when the single payment property on the quote is removed
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer[]|\ArrayObject
     */
    protected function getPaymentCollection(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $result = new ArrayObject();
        foreach ($calculableObjectTransfer->getPayments() as $payment) {
            $result[] = $payment;
        }

        $singlePayment = $calculableObjectTransfer->getPayment();

        if ($singlePayment) {
            $result[] = $singlePayment;
        }

        return $result;
    }

}
