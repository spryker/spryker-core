<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\OrderTransfer;

class GrandTotal implements OrderAmountAggregatorInterface
{
    /**
     * @param OrderTransfer $orderTransfer
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
        $totalsTransfer = $orderTransfer->getTotals();

        $grandTotal = $this->getCalculatedGrandTotal($orderTransfer);
        $totalsTransfer->setGrandTotal($grandTotal);
    }


    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return int
     */
    protected function getCalculatedGrandTotal(OrderTransfer $orderTransfer)
    {
        $orderTransfer->getTotals()->requireSubtotal();

        $subTotal = $orderTransfer->getTotals()->getSubtotal();
        $expensesTotal = $orderTransfer->getTotals()->getExpenseTotal();

        $grandTotal = $subTotal + $expensesTotal;

        return $grandTotal;
    }
}
