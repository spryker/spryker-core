<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund\Business\Model\RefundCalculator;

use Generated\Shared\Transfer\ItemTransfer;

abstract class AbstractRefundCalculator implements RefundCalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $salesOrderItems
     *
     * @return bool
     */
    protected function shouldItemRefunded(ItemTransfer $itemTransfer, array $salesOrderItems)
    {
        foreach ($salesOrderItems as $salesOrderItem) {
            if ($salesOrderItem->getIdSalesOrderItem() === $itemTransfer->getIdSalesOrderItem()) {
                return true;
            }
        }

        return false;
    }
}
