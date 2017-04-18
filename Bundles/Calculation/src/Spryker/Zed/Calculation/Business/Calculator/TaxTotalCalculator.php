<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Calculator;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TaxTotalTransfer;
use Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface;

class TaxTotalCalculator implements CalculatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->requireTotals();

        $totalTaxAmount = 0;
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $totalTaxAmount += $itemTransfer->getTaxAmountFullAggregation();
        }

        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            $totalTaxAmount += $expenseTransfer->getSumTaxAmount();
        }

        $taxTotalTransfer = new TaxTotalTransfer();
        $taxTotalTransfer->setAmount($totalTaxAmount);

        $quoteTransfer->getTotals()->setTaxTotal($taxTotalTransfer);

    }
}
