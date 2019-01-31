<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;

class ExpenseTotalCalculator implements CalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $calculableObjectTransfer->requireTotals();

        $expenseTotal = $this->calculateExpenseTotalSumPrice($calculableObjectTransfer->getExpenses());
        $expenseTotal += $this->calculateItemExpenseTotalSumPrice($calculableObjectTransfer->getItems());

        $calculableObjectTransfer->getTotals()->setExpenseTotal($expenseTotal);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenses
     *
     * @return int
     */
    protected function calculateExpenseTotalSumPrice(ArrayObject $expenses)
    {
        $expenseTotal = 0;
        foreach ($expenses as $expenseTransfer) {
            $expenseTotal += $expenseTransfer->getSumPrice();
        }
        return $expenseTotal;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return int
     */
    protected function calculateItemExpenseTotalSumPrice(ArrayObject $items): int
    {
        $expenseTotal = 0;
        foreach ($items as $itemTransfer) {
            if ($this->assertItemHasNoExpenseRequirements($itemTransfer)) {
                continue;
            }

            $expenseTotal += $itemTransfer->getShipment()->getExpense()->getSumPrice();
        }

        return $expenseTotal;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function assertItemHasNoExpenseRequirements(ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getShipment() === null || $itemTransfer->getShipment()->getExpense() === null;
    }
}
