<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Expander;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;

class ProductBundleExpander implements ProductBundleExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandUniqueOrderItemsWithProductBundles(array $itemTransfers, OrderTransfer $orderTransfer): array
    {
        $itemTransfers = $this->extractItemsNotRelatedToBundle($itemTransfers);

        $bundleItems = $this->getUniqueBundleItems($orderTransfer);

        return array_merge($itemTransfers, $bundleItems);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function extractItemsNotRelatedToBundle(array $itemTransfers): array
    {
        $uniqueItemTransfers = [];

        foreach ($itemTransfers as $itemTransfer) {
            if ($itemTransfer->getRelatedBundleItemIdentifier()) {
                continue;
            }

            $uniqueItemTransfers[] = $itemTransfer;
        }

        return $uniqueItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getUniqueBundleItems(OrderTransfer $orderTransfer): array
    {
        $uniqueItemTransfers = [];

        foreach ($orderTransfer->getBundleItems() as $itemTransfer) {
            $bundleItemIdentifier = $itemTransfer->requireBundleItemIdentifier()->getBundleItemIdentifier();

            if (!isset($calculatedOrderItems[$bundleItemIdentifier])) {
                $uniqueItemTransfers[$bundleItemIdentifier] = clone $itemTransfer;

                continue;
            }

            $uniqueItemTransfers[$bundleItemIdentifier] = $this->setQuantityAndPriceOfUniqueOrderItem($uniqueItemTransfers[$bundleItemIdentifier], $itemTransfer);
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
