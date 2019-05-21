<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\TaxTotalTransfer;

class TaxTotalCalculator implements CalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $calculableObjectTransfer->requireTotals();

        $totalTaxAmount = $this->calculateTaxTotalForItems($calculableObjectTransfer->getItems());
        $totalTaxAmount += $this->calculateTaxTotalAmountForExpenses($calculableObjectTransfer->getExpenses());

        $taxTotalTransfer = new TaxTotalTransfer();
        $taxTotalTransfer->setAmount((int)round($totalTaxAmount));

        $calculableObjectTransfer->getTotals()->setTaxTotal($taxTotalTransfer);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return int
     */
    protected function calculateTaxTotalForItems(ArrayObject $items)
    {
        $totalTaxAmount = 0;
        foreach ($items as $itemTransfer) {
            $totalTaxAmount += $itemTransfer->getSumTaxAmountFullAggregation();
        }

        return $totalTaxAmount;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenses
     *
     * @return int
     */
    protected function calculateTaxTotalAmountForExpenses(ArrayObject $expenses)
    {
        $totalTaxAmount = 0;
        foreach ($expenses as $expenseTransfer) {
            $totalTaxAmount += $expenseTransfer->getSumTaxAmount();
        }

        return $totalTaxAmount;
    }
}
