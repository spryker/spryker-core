<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpyOmsOrderItemStateEntityTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;

class OrderItemScheduleExpander implements OrderItemScheduleExpanderInterface
{
    public function expandOrderItemWithScheduleTime(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer,
        SpySalesOrderItemEntityTransfer $salesOrderItemEntityTransfer
    ): SpySalesOrderItemEntityTransfer {
        if (!$this->hasScheduledTime($itemTransfer)) {
            return $salesOrderItemEntityTransfer;
        }

        $salesOrderItemEntityTransfer = $this->setOrderItemState($itemTransfer, $salesOrderItemEntityTransfer);

        return $salesOrderItemEntityTransfer;
    }

    protected function hasScheduledTime(ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getMetadata() && $itemTransfer->getMetadata()->getScheduledAt();
    }

    protected function setOrderItemState(
        ItemTransfer $itemTransfer,
        SpySalesOrderItemEntityTransfer $salesOrderItemEntityTransfer
    ): SpySalesOrderItemEntityTransfer {
        if ($itemTransfer->getFkOmsOrderItemState() !== null) {
            $salesOrderItemEntityTransfer->setFkOmsOrderItemState($itemTransfer->getFkOmsOrderItemState());
        }

        if ($itemTransfer->getState()) {
            $initialOmsOrderItemStateEntityTransfer = (new SpyOmsOrderItemStateEntityTransfer())
                ->fromArray($itemTransfer->getState()->toArray(), true);

            $salesOrderItemEntityTransfer->setState($initialOmsOrderItemStateEntityTransfer);
        }

        return $salesOrderItemEntityTransfer;
    }
}
