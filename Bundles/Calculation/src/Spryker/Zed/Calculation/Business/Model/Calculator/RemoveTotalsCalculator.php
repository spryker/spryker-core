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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $totalsTransfer = $this->createTotalsTransfer();
        $totalsTransfer->setTaxTotal($this->createTaxTotalsTransfer());
        $totalsTransfer->setDiscount($this->createDiscountTotalsTransfer());
        $totalsTransfer->setExpenses($this->createExpenseTotalsTransfer());

        $quoteTransfer->setTotals($totalsTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\TotalsTransfer
     */
    protected function createTotalsTransfer()
    {
        return new TotalsTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\TaxTotalTransfer
     */
    protected function createTaxTotalsTransfer()
    {
        return new TaxTotalTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\DiscountTotalsTransfer
     */
    protected function createDiscountTotalsTransfer()
    {
        return new DiscountTotalsTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\ExpenseTotalsTransfer
     */
    protected function createExpenseTotalsTransfer()
    {
        return new ExpenseTotalsTransfer();
    }

}
