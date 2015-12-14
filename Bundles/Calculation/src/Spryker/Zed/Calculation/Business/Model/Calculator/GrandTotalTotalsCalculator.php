<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\QuoteTransfer;

class GrandTotalTotalsCalculator implements CalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->requireTotals();

        $grandTotal = $this->getCalculatedGrandTotal($quoteTransfer);
        $quoteTransfer->getTotals()->setGrandTotal($grandTotal);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function getCalculatedGrandTotal(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->getTotals()->requireSubtotal();

        $subTotal = $quoteTransfer->getTotals()->getSubtotal();
        $expensesTotal = $this->getExpensesTotal($quoteTransfer);

        $grandTotal = $subTotal + $expensesTotal;

        return $grandTotal;
    }



    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function getExpensesTotal(QuoteTransfer $quoteTransfer)
    {
        $expensesTotalTransfer = $quoteTransfer->getTotals()->getExpenses();
        $expensesTotal = 0;
        if ($expensesTotalTransfer !== null) {
            $expensesTotal = $expensesTotalTransfer->getTotalAmount();
        }

        return $expensesTotal;
    }
}
