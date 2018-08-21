<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;

class RefundTotalCalculator implements CalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $calculableObjectTransfer->requireTotals();

        $refundTotalAmount = $this->calculateItemRefundAmount($calculableObjectTransfer);
        $refundTotalAmount += $this->calculateExpenseRefundAmount($calculableObjectTransfer);

        $calculableObjectTransfer->getTotals()->setRefundTotal($refundTotalAmount);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return int
     */
    protected function calculateItemRefundAmount(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $refundTotalAmount = 0;
        foreach ($calculableObjectTransfer->getItems() as $itemTransfer) {
            $refundTotalAmount += $itemTransfer->getRefundableAmount();
            $refundTotalAmount += $this->calculateItemOptionTotalRefundAmount($itemTransfer);
        }
        return $refundTotalAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return mixed
     */
    protected function calculateItemOptionTotalRefundAmount(ItemTransfer $itemTransfer)
    {
        $refundTotalAmount = 0;
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $refundTotalAmount += $productOptionTransfer->getRefundableAmount();
        }
        return $refundTotalAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return int
     */
    protected function calculateExpenseRefundAmount(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $refundTotalAmount = 0;
        foreach ($calculableObjectTransfer->getExpenses() as $expenseTransfer) {
            $refundTotalAmount += $expenseTransfer->getRefundableAmount();
        }
        return $refundTotalAmount;
    }
}
