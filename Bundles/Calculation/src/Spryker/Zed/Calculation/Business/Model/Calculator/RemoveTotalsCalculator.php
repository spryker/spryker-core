<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator;

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
        $totalsTransfer->setDiscountTotal(0);
        $totalsTransfer->setExpenseTotal(0);

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

}
