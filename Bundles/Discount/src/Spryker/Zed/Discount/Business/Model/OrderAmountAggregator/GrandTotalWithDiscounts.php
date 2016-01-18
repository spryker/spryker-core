<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\OrderTransfer;

class GrandTotalWithDiscounts
{
    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
        $orderTransfer->requireTotals();

        $totalDiscountAmount = $orderTransfer->getTotals()->getDiscount()->getTotalAmount();

        $orderTransfer->getTotals()->setGrandTotal(
            $orderTransfer->getTotals()->getGrandTotal() - $totalDiscountAmount
        );
    }
}
