<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\DiscountTotalsTransfer;
use Generated\Shared\Transfer\ExpenseTotalsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TaxTotalTransfer;
use Generated\Shared\Transfer\TotalsTransfer;

class RemoveTotalsCalculator implements CalculatorInterface
{

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $totalsTransfer = $this->createTotalsTransfer();
        $totalsTransfer->setTaxTotal($this->createTaxTotalsTransfer());
        $totalsTransfer->setDiscountTotal(0);
        $totalsTransfer->setExpenseTotal(0);

        $quoteTransfer->setTotals($totalsTransfer);
    }

    /**
     * @return TotalsTransfer
     */
    protected function createTotalsTransfer()
    {
        return new TotalsTransfer();
    }

    /**
     * @return TaxTotalTransfer
     */
    protected function createTaxTotalsTransfer()
    {
        return new TaxTotalTransfer();
    }
}
