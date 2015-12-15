<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\QuoteTransfer;

class SubtotalTotalsCalculator implements CalculatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->requireTotals();

        $subTotal = $this->getCalculatedSubtotal($quoteTransfer);
        $quoteTransfer->getTotals()->setSubtotal($subTotal);
    }

    /**
     * QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function getCalculatedSubtotal(QuoteTransfer $quoteTransfer)
    {
        $subTotal = 0;
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemTransfer->requireSumGrossPriceWithProductOptions();
            $subTotal += $itemTransfer->getSumGrossPriceWithProductOptions();
        }

        return $subTotal;
    }

}
