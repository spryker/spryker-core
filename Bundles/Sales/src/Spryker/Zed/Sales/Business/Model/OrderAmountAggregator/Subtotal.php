<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;

class Subtotal implements OrderAmountAggregatorInterface
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

        $subTotal = $this->getSumOfItemGrossAmount($orderTransfer);

        $orderTotalsTransfer->setSubtotal($subTotal);
        $orderTransfer->setTotals($orderTotalsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    protected function getSumOfItemGrossAmount(OrderTransfer $orderTransfer)
    {
        $subTotal = 0;
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $itemTransfer->requireSumGrossPrice();
            $subTotal += $itemTransfer->getSumGrossPrice();
        }
        return $subTotal;
    }
}
