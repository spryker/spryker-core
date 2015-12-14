<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\ExpenseTotalsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class ExpenseTotalsCalculator implements CalculatorInterface
{
    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->requireTotals();

        $expenseTotalTransfer = $this->createExpenseTotalTransfer();
        $expenseTotalTransfer->setTotalAmount($this->getCalculatedExpenseTotalAmount($quoteTransfer));

        $quoteTransfer->getTotals()->setExpenses($expenseTotalTransfer);
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function getCalculatedExpenseTotalAmount(QuoteTransfer $quoteTransfer)
    {
        $totalExpenseAmount = 0;
        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            $expenseTransfer->requireSumGrossPrice();
            $totalExpenseAmount += $expenseTransfer->getSumGrossPrice();
        }

        return $totalExpenseAmount;
    }

    /**
     * @return ExpenseTotalsTransfer
     */
    protected function createExpenseTotalTransfer()
    {
        return new ExpenseTotalsTransfer();
    }

}
