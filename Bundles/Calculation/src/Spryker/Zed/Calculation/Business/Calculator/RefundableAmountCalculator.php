<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Calculator;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface;

class RefundableAmountCalculator implements CalculatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemTransfer->requireSumAggregation();

            $totalCanceledAmount = $itemTransfer->getCanceledAmount();
            foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
                $totalCanceledAmount += $productOptionTransfer->getCanceledAmount();
            }
            $itemTransfer->setRefundableAmount($itemTransfer->getSumAggregation() - $totalCanceledAmount);
        }

        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            $expenseTransfer->setRefundableAmount(0);
        }
    }
}
