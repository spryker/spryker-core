<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\QuoteTransfer;

class ExpenseTotalsCalculator implements CalculatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->requireTotals();

        $quoteTransfer->getTotals()
            ->setExpenseTotal(
                $this->getCalculatedExpenseTotalAmount($quoteTransfer)
            );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
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
