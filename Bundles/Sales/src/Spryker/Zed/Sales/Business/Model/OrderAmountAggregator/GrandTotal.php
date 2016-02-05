<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;

class GrandTotal implements OrderAmountAggregatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
        $orderTotalsTransfer = $orderTransfer->getTotals();
        if ($orderTotalsTransfer === null) {
            $orderTotalsTransfer = new TotalsTransfer();
        }

        $grandTotal = $this->getCalculatedGrandTotal($orderTransfer);
        $orderTotalsTransfer->setGrandTotal($grandTotal);
    }


    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
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
