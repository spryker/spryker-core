<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Calculator;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface;

class DiscountTotalCalculator implements CalculatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->requireTotals();

        $totalDiscountAmount = $this->calculateItemTotalDiscountAmount($quoteTransfer);
        $totalDiscountAmount += $this->calculateExpenseTotalDiscountAmount($quoteTransfer);

        $quoteTransfer->getTotals()->setDiscountTotal($totalDiscountAmount);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function calculateExpenseTotalDiscountAmount(QuoteTransfer $quoteTransfer)
    {
        $totalDiscountAmount = 0;
        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            $totalDiscountAmount += $expenseTransfer->getDiscountAmountAggregation();
        }
        return $totalDiscountAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function calculateItemTotalDiscountAmount(QuoteTransfer $quoteTransfer)
    {
        $totalDiscountAmount = 0;
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $totalDiscountAmount += $itemTransfer->getDiscountAmountFullAggregation();
        }
        return $totalDiscountAmount;
    }
}
