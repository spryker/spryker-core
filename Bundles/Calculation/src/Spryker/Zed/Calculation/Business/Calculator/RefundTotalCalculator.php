<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Calculator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface;

class RefundTotalCalculator implements CalculatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->requireTotals();

        $refundTotalAmount = $this->calculateItemRefundAmount($quoteTransfer);
        $refundTotalAmount += $this->calculateExpenseRefundAmount($quoteTransfer);

        $quoteTransfer->getTotals()->setRefundTotal($refundTotalAmount);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function calculateItemRefundAmount(QuoteTransfer $quoteTransfer)
    {
        $refundTotalAmount = 0;
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $refundTotalAmount += $itemTransfer->getRefundableAmount();
            $refundTotalAmount += $this->calculateItemOptionTotalRefundAmount($itemTransfer);
        }
        return $refundTotalAmount;
    }

    /**
     * @param ItemTransfer $itemTransfer
     *
     * @return mixed
     */
    protected function calculateItemOptionTotalRefundAmount(ItemTransfer $itemTransfer)
    {
        $refundTotalAmount = 0;
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $refundTotalAmount += $productOptionTransfer->getRefundableAmount();
        }
        return $refundTotalAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function calculateExpenseRefundAmount(QuoteTransfer $quoteTransfer)
    {
        $refundTotalAmount = 0;
        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            $refundTotalAmount += $expenseTransfer->getRefundableAmount();
        }
        return $refundTotalAmount;
    }
}
