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
     * @var \Spryker\Zed\SalesExtension\Dependency\Plugin\UniqueOrderItemsExpanderPluginInterface[]
     */
    protected $uniqueOrderItemsExpanderPlugins;

    /**
     * @param \Spryker\Zed\SalesExtension\Dependency\Plugin\UniqueOrderItemsExpanderPluginInterface[] $uniqueOrderItemsExpanderPlugins
     */
    public function __construct(array $uniqueOrderItemsExpanderPlugins)
    {
        $this->uniqueOrderItemsExpanderPlugins = $uniqueOrderItemsExpanderPlugins;
    }

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
        $itemTransfers = $this->getUniqueItems($orderTransfer);

        $itemTransfers = $this->executeUniqueOrderItemsExpanderPlugins($itemTransfers, $orderTransfer);

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function executeUniqueOrderItemsExpanderPlugins(array $itemTransfers, OrderTransfer $orderTransfer): array
    {
        foreach ($this->uniqueOrderItemsExpanderPlugins as $uniqueOrderItemsExpanderPlugin) {
            $itemTransfers = $uniqueOrderItemsExpanderPlugin->expand($itemTransfers, $orderTransfer);
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getUniqueItems(OrderTransfer $orderTransfer): array
    {
        $uniqueItemTransfers = [];

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $key = $itemTransfer->requireGroupKey()->getGroupKey();

            if (!isset($uniqueItemTransfers[$key])) {
                $uniqueItemTransfers[$key] = clone $itemTransfer;

                continue;
            }

            $uniqueItemTransfers[$key] = $this->setQuantityAndPriceOfUniqueOrderItem($uniqueItemTransfers[$key], $itemTransfer);
        }

        return array_values($uniqueItemTransfers);
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
