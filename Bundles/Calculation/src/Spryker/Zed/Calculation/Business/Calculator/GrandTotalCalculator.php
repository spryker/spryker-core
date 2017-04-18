<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Calculator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface;

class GrandTotalCalculator implements CalculatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $this->calculateGrandTotal($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    protected function calculateGrandTotal(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->requireTotals();

        $subtotal = $quoteTransfer->getTotals()->getSubtotal();
        $expenseTotal = $quoteTransfer->getTotals()->getExpenseTotal();
        $discountAmount = $quoteTransfer->getTotals()->getDiscountTotal();

        $grandTotal = $subtotal + $expenseTotal - $discountAmount;

        if ($grandTotal < 0) {
            $grandTotal = 0;
        }

        $quoteTransfer->getTotals()->setGrandTotal($grandTotal);
    }
}
