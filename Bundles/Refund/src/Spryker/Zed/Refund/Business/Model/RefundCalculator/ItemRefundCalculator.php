<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund\Business\Model\RefundCalculator;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RefundTransfer;

class ItemRefundCalculator extends AbstractRefundCalculator
{

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] array $salesOrderItems
     *
     * @return \Generated\Shared\Transfer\RefundTransfer
     */
    public function calculateRefund(RefundTransfer $refundTransfer, OrderTransfer $orderTransfer, array $salesOrderItems)
    {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
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
            $itemTransfer->setCanceledAmount($itemTransfer->getRefundableAmount());
        }
    }

}
