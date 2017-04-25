<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Calculator;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Calculation\Business\Calculator\CalculatorInterface;

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

        $calculableObjectTransfer->getTotals()->setExpenseTotal($expenseTotal);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenses
     *
     * @return int
     */
    protected function calculateExpenseTotalSumPrice(\ArrayObject $expenses)
    {
        $expenseTotal = 0;
        foreach ($expenses as $expenseTransfer) {
            $expenseTotal += $expenseTransfer->getSumPrice();
        }
        return $expenseTotal;
    }
}
