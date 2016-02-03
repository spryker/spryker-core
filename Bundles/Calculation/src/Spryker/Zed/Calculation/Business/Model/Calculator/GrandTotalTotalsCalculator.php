<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Library\Hash\Hash;

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

        $totalsTransfer = $quoteTransfer->getTotals();

        $grandTotal = $this->getCalculatedGrandTotal($quoteTransfer);
        $totalsTransfer->setGrandTotal($grandTotal);

        $totalsHash = $this->generateTotalsHash($grandTotal);
        $totalsTransfer->setHash($totalsHash);
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
        $expensesTotal = $quoteTransfer->getTotals()->getExpenseTotal();

        $grandTotal = $subTotal + $expensesTotal;

        return $grandTotal;
    }

    /**
     * @param int $grandTotal
     *
     * @return string
     */
    protected function generateTotalsHash($grandTotal)
    {
        return Hash::hashValue(Hash::SHA256, $grandTotal);
    }

}
