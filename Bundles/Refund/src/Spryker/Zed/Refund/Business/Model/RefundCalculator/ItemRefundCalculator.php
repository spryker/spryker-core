<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund\Business\Model\RefundCalculator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RefundTransfer;

class ItemRefundCalculator extends AbstractRefundCalculator
{
    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $salesOrderItems
     *
     * @return \Generated\Shared\Transfer\RefundTransfer
     */
    public function calculateRefund(RefundTransfer $refundTransfer, OrderTransfer $orderTransfer, array $salesOrderItems)
    {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if ($this->isItemAdded($refundTransfer, $itemTransfer)) {
                continue;
            }

            if ($this->shouldItemRefunded($itemTransfer, $salesOrderItems)) {
                $refundTransfer->addItem($itemTransfer);
            }
        }

        $this->calculateRefundableItemAmount($refundTransfer);
        $this->setCanceledItemAmount($refundTransfer);

        return $refundTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     *
     * @return void
     */
    protected function calculateRefundableItemAmount(RefundTransfer $refundTransfer)
    {
        foreach ($refundTransfer->getItems() as $itemTransfer) {
            $this->calculateProductOptionRefundAmount($refundTransfer, $itemTransfer);

            $refundTransfer->setAmount($refundTransfer->getAmount() + $itemTransfer->getRefundableAmount());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     *
     * @return void
     */
    protected function setCanceledItemAmount(RefundTransfer $refundTransfer)
    {
        foreach ($refundTransfer->getItems() as $itemTransfer) {
            $this->calculateProductOptionCanceledAmount($itemTransfer);

            $itemTransfer->setCanceledAmount($itemTransfer->getRefundableAmount());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function calculateProductOptionRefundAmount(RefundTransfer $refundTransfer, ItemTransfer $itemTransfer)
    {
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $refundTransfer->setAmount($refundTransfer->getAmount() + $productOptionTransfer->getRefundableAmount());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function calculateProductOptionCanceledAmount(ItemTransfer $itemTransfer)
    {
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $productOptionTransfer->setCanceledAmount($productOptionTransfer->getRefundableAmount());
        }
    }
}
