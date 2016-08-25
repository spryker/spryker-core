<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Business\Model;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TaxTotalTransfer;

class TaxCalculation implements CalculatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $totalTaxAmount = 0;
        $totalTaxAmount += $this->sumItemTaxes($quoteTransfer);
        $totalTaxAmount += $this->sumExpenseTaxes($quoteTransfer);

        $this->setTaxTotals($quoteTransfer, $totalTaxAmount);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $taxAmount
     *
     * @return void
     */
    protected function setTaxTotals(QuoteTransfer $quoteTransfer, $taxAmount)
    {
        $taxTotalTransfer = new TaxTotalTransfer();

        $taxAmount = (int)round($taxAmount);
        $taxTotalTransfer->setAmount($taxAmount);

        $quoteTransfer->getTotals()->setTaxTotal($taxTotalTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function sumExpenseTaxes(QuoteTransfer $quoteTransfer)
    {
        $totalTaxAmount = 0;
        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            $totalTaxAmount += $expenseTransfer->getSumTaxAmount();
        }

        return $totalTaxAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function sumItemTaxes(QuoteTransfer $quoteTransfer)
    {
        $totalTaxAmount = 0;
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $totalTaxAmount += $itemTransfer->getSumTaxAmount();
        }

        return $totalTaxAmount;
    }

}
