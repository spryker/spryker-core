<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\QuoteTransfer;

class ExpenseGrossSumAmountCalculator implements CalculatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            $expenseTransfer->requireUnitGrossPrice()->requireQuantity();
            $expenseTransfer->setSumGrossPrice($expenseTransfer->getUnitGrossPrice() * $expenseTransfer->getQuantity());
        }
    }
}
