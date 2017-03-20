<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\QuoteTransfer;

class ExpenseGrossSumAmountCalculator implements CalculatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            $expenseTransfer->requireUnitGrossPrice()->requireQuantity();
            $expenseTransfer->setSumGrossPrice($expenseTransfer->getUnitGrossPrice() * $expenseTransfer->getQuantity());

            $expenseTransfer->setUnitItemTotal($expenseTransfer->getUnitGrossPrice());
            $expenseTransfer->setSumItemTotal($expenseTransfer->getSumGrossPrice());

        }
    }

}
