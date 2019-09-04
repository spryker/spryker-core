<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\OrderItem;

use Generated\Shared\Transfer\ItemTransfer;

class SalesOrderItemGrouper implements SalesOrderItemGrouperInterface
{
    /**
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function getUniqueOrderItems(iterable $itemTransfers): array
    {
        $calculatedOrderItems = [];
        foreach ($itemTransfers as $itemTransfer) {
            $key = $itemTransfer->requireGroupKey()->getGroupKey();

            if (!isset($calculatedOrderItems[$key])) {
                $calculatedOrderItems[$key] = clone $itemTransfer;
                continue;
            }

            $calculatedOrderItems[$key] = $this->setQuantityAndPriceOfUniqueOrderItem($calculatedOrderItems[$key], $itemTransfer);
        }

        return $calculatedOrderItems;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $calculatedOrderItem
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function setQuantityAndPriceOfUniqueOrderItem(ItemTransfer $calculatedOrderItem, ItemTransfer $itemTransfer): ItemTransfer
    {
        $calculatedOrderItem->setQuantity($calculatedOrderItem->getQuantity() + $itemTransfer->getQuantity());
        $calculatedOrderItem->setSumPrice($calculatedOrderItem->getSumPrice() + $itemTransfer->getSumPrice());

        return $calculatedOrderItem;
    }
}
