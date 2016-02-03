<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;

class Item implements OrderAmountAggregatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $this->assertItemRequirements($itemTransfer);
            $itemTransfer->setSumGrossPrice($itemTransfer->getUnitGrossPrice() * $itemTransfer->getQuantity());
            $itemTransfer->setRefundableAmount($itemTransfer->getSumGrossPrice() - $itemTransfer->getCanceledAmount());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function assertItemRequirements(ItemTransfer $itemTransfer)
    {
        $itemTransfer->requireUnitGrossPrice()
            ->requireQuantity();
    }

}
