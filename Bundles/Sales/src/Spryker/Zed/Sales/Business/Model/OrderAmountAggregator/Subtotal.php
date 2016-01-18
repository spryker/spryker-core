<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;

class Subtotal
{
    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
        $orderTotalsTransfer = $orderTransfer->getTotals();
        if ($orderTotalsTransfer === null) {
            $orderTotalsTransfer = new TotalsTransfer();
        }

        $subTotal = $this->getSumOfItemGrossAmount($orderTransfer);

        $orderTotalsTransfer->setSubtotal($subTotal);
        $orderTransfer->setTotals($orderTotalsTransfer);
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return int
     */
    protected function getSumOfItemGrossAmount(OrderTransfer $orderTransfer)
    {
        $subTotal = 0;
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $itemTransfer->requireSumGrossPriceWithProductOptions();
            $subTotal += $itemTransfer->getSumGrossPriceWithProductOptions();
        }
        return $subTotal;
    }
}
