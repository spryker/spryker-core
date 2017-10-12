<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\CalculableObjectTransfer;

class InitialGrandTotalCalculator implements CalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $calculableObjectTransfer->requireTotals();

        $grandTotal = 0;
        $grandTotal = $this->calculateItemGrandTotal($calculableObjectTransfer, $grandTotal);
        $grandTotal = $this->calculateExpenseGrandTotal($calculableObjectTransfer, $grandTotal);

        $calculableObjectTransfer->getTotals()->setGrandTotal($grandTotal);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     * @param int $grandTotal
     *
     * @return int
     */
    protected function calculateItemGrandTotal(CalculableObjectTransfer $calculableObjectTransfer, $grandTotal)
    {
        foreach ($calculableObjectTransfer->getItems() as $itemTransfer) {
            $grandTotal += $itemTransfer->getSumSubtotalAggregation();
        }

        return $grandTotal;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     * @param int $grandTotal
     *
     * @return int
     */
    protected function calculateExpenseGrandTotal(CalculableObjectTransfer $calculableObjectTransfer, $grandTotal)
    {
        foreach ($calculableObjectTransfer->getExpenses() as $expenseTransfer) {
            $grandTotal += $expenseTransfer->getSumPrice();
        }

        return $grandTotal;
    }
}
