<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\OrderItem;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;

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
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function getUniqueItemsFromOrder(OrderTransfer $orderTransfer): array
    {
        $calculatedOrderItems = $this->getUniqueItems($orderTransfer);
        $calculatedOrderBundleItems = $this->getUniqueBundleItems($orderTransfer);

        return array_merge($calculatedOrderItems, $calculatedOrderBundleItems);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getUniqueItems(OrderTransfer $orderTransfer): array
    {
        $calculatedOrderItems = [];

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getRelatedBundleItemIdentifier()) {
                continue;
            }

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
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getUniqueBundleItems(OrderTransfer $orderTransfer): array
    {
        $calculatedOrderBundleItems = [];

        foreach ($orderTransfer->getBundleItems() as $itemTransfer) {
            $bundleItemIdentifier = $itemTransfer->requireBundleItemIdentifier()->getBundleItemIdentifier();

            if (!isset($calculatedOrderItems[$bundleItemIdentifier])) {
                $calculatedOrderBundleItems[$bundleItemIdentifier] = clone $itemTransfer;
                continue;
            }

            $calculatedOrderBundleItems[$bundleItemIdentifier] = $this->setQuantityAndPriceOfUniqueOrderItem($calculatedOrderBundleItems[$bundleItemIdentifier], $itemTransfer);
        }

        return array_values($calculatedOrderBundleItems);
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
