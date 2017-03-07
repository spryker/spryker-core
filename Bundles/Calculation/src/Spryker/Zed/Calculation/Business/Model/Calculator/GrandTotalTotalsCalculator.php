<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Service\UtilText\Model\Hash;
use Spryker\Service\UtilText\UtilTextService;

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
        $utilTextService = new UtilTextService();

        return $utilTextService->hashValue($grandTotal, Hash::SHA256);
    }

}
