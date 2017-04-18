<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Aggregator;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface;

class ExpenseDiscountAmountAggregator implements CalculatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            $totalDiscountAmount = 0;
            foreach ($expenseTransfer->getCalculatedDiscounts() as $calculatedDiscountTransfer) {
                $this->setCalculatedDiscountsSumGrossAmount($calculatedDiscountTransfer);
                $totalDiscountAmount += $calculatedDiscountTransfer->getSumGrossAmount();
            }
            $expenseTransfer->setDiscountAmountAggregation($totalDiscountAmount);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CalculatedDiscountTransfer $calculatedDiscountTransfer
     */
    protected function setCalculatedDiscountsSumGrossAmount(CalculatedDiscountTransfer $calculatedDiscountTransfer)
    {
        $calculatedDiscountTransfer->setSumGrossAmount($calculatedDiscountTransfer->getUnitGrossAmount() * $calculatedDiscountTransfer->getQuantity());
    }
}
