<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund\Communication\Plugin;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Refund\Business\RefundFacade getFacade()
 * @method \Spryker\Zed\Refund\Communication\RefundCommunicationFactory getFactory()
 */
class RefundCalculatorPlugin extends AbstractPlugin implements RefundCalculatorPluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] array $salesOrderItems
     *
     * @return void
     */
    public function calculateRefund(RefundTransfer $refundTransfer, OrderTransfer $orderTransfer, array $salesOrderItems)
    {
        $refundedItemAmount = 0;
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if ($this->shouldItemRefunded($itemTransfer, $salesOrderItems)) {
                $refundTransfer->addItem($itemTransfer);
            } else {
                $refundedItemAmount += $itemTransfer->getRefundableAmount();
            }
        }

        if ($refundedItemAmount === 0) {
            $refundTransfer->setExpenses($orderTransfer->getExpenses());
        }

        $this->calculateRefundableAmount($refundTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $salesOrderItems
     *
     * @return bool
     */
    protected function shouldItemRefunded(ItemTransfer $itemTransfer, array $salesOrderItems)
    {
        foreach ($salesOrderItems as $salesOrderItem) {
            if ($itemTransfer->getIdSalesOrderItem() === $salesOrderItem->getIdSalesOrderItem()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     *
     * @return void
     */
    protected function calculateRefundableAmount(RefundTransfer $refundTransfer)
    {
        foreach ($refundTransfer->getItems() as $itemTransfer) {
            $refundTransfer->setAmount($refundTransfer->getAmount() + $itemTransfer->getRefundableAmount());
            $itemTransfer->setCanceledAmount($itemTransfer->getRefundableAmount());
        }

        if ($refundTransfer->getExpenses()) {
            foreach ($refundTransfer->getExpenses() as $expenseTransfer) {
                $refundTransfer->setAmount($refundTransfer->getAmount() + $expenseTransfer->getRefundableAmount());
                $expenseTransfer->setCanceledAmount($expenseTransfer->getRefundableAmount());
            }
        }
    }

}
