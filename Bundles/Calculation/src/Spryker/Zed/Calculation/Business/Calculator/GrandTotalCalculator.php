<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Calculator;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\Calculation\Business\Calculator\CalculatorInterface;

class GrandTotalCalculator implements CalculatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $calculableObjectTransfer->requireTotals();

        $grandTotal = $this->calculateGrandTotal($calculableObjectTransfer->getTotals());

        $calculableObjectTransfer->getTotals()->setGrandTotal($grandTotal);
    }

    /**
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     *
     * @return int
     */
    protected function calculateGrandTotal(TotalsTransfer $totalsTransfer)
    {
        $subtotal = $totalsTransfer->getSubtotal();
        $expenseTotal = $totalsTransfer->getExpenseTotal();
        $discountTotal = $totalsTransfer->getDiscountTotal();
        $canceledTotal = $totalsTransfer->getCanceledTotal();

        $grandTotal = $subtotal + $expenseTotal - $discountTotal - $canceledTotal;

        if ($grandTotal < 0) {
            $grandTotal = 0;
        }

        return $grandTotal;
    }
}
