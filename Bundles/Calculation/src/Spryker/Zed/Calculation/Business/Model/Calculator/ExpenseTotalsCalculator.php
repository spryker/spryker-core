<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator;

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

        $quoteTransfer->getTotals()
            ->setExpenseTotal($this->getCalculatedExpenseTotalAmount($quoteTransfer));
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
}
