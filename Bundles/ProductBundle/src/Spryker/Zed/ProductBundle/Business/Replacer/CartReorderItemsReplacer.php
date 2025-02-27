<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\Replacer;

use ArrayObject;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;

class CartReorderItemsReplacer implements CartReorderItemsReplacerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function replaceCartReorderItems(CartReorderTransfer $cartReorderTransfer): CartReorderTransfer
    {
        $productBundleItems = $this->extractProductBundleItemsFromOrderTransfer($cartReorderTransfer->getOrderOrFail());
        if ($productBundleItems === []) {
            return $cartReorderTransfer;
        }
        $filteredProductBundleItems = $this->filterOutProductBundleItemsByOrderItemTransfers(
            $cartReorderTransfer->getOrderItems(),
            $productBundleItems,
        );
        if ($filteredProductBundleItems === []) {
            return $cartReorderTransfer;
        }

        $productBundleItemsIndexedByProductBundleIdentifier = $this->getProductBundleItemsIndexedByProductBundleIdentifier(
            $filteredProductBundleItems,
        );
        if ($productBundleItemsIndexedByProductBundleIdentifier === []) {
            return $cartReorderTransfer;
        }

        $bundledItemTransfersGroupedByProductBundleIdentifier = $this->getBundledItemTransfersGroupedByProductBundleIdentifier(
            $filteredProductBundleItems,
        );

        foreach ($productBundleItemsIndexedByProductBundleIdentifier as $bundleItemIdentifier => $productBundleItemTransfer) {
            $firstBundledItemIndex = array_key_first($bundledItemTransfersGroupedByProductBundleIdentifier[$bundleItemIdentifier]);
            $firstItemInBundle = $bundledItemTransfersGroupedByProductBundleIdentifier[$bundleItemIdentifier][$firstBundledItemIndex];

            $productBundleItemTransfer
                ->setIdSalesOrderItem($firstItemInBundle->getIdSalesOrderItemOrFail())
                ->setGroupKey($firstItemInBundle->getGroupKeyOrFail());

            $cartReorderTransfer = $this->removeRelatedBundledItems($cartReorderTransfer, $bundleItemIdentifier);
            $cartReorderTransfer->getOrderItems()->offsetSet($firstBundledItemIndex, $productBundleItemTransfer);
        }

        return $cartReorderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function extractProductBundleItemsFromOrderTransfer(OrderTransfer $orderTransfer): array
    {
        $productBundleItems = [];
        foreach ($orderTransfer->getItems() as $index => $itemTransfer) {
            if ($itemTransfer->getProductBundle() && $itemTransfer->getRelatedBundleItemIdentifier()) {
                $productBundleItems[$index] = $itemTransfer;
            }
        }

        return $productBundleItems;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $orderItemTransfers
     * @param array<int, \Generated\Shared\Transfer\ItemTransfer> $productBundleItemTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function filterOutProductBundleItemsByOrderItemTransfers(ArrayObject $orderItemTransfers, array $productBundleItemTransfers): array
    {
        $filteredItemTransfers = [];
        foreach ($productBundleItemTransfers as $index => $productBundleItemTransfer) {
            if ($this->isProductBundleItemInOrderItemTransfers($productBundleItemTransfer, $orderItemTransfers)) {
                $filteredItemTransfers[$index] = $productBundleItemTransfer;
            }
        }

        return $filteredItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $productBundleItemTransfer
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $orderItemTransfers
     *
     * @return bool
     */
    protected function isProductBundleItemInOrderItemTransfers(
        ItemTransfer $productBundleItemTransfer,
        ArrayObject $orderItemTransfers
    ): bool {
        foreach ($orderItemTransfers as $orderItemTransfer) {
            if (
                $orderItemTransfer->getRelatedBundleItemIdentifier()
                && $orderItemTransfer->getIdSalesOrderItemOrFail() === $productBundleItemTransfer->getIdSalesOrderItemOrFail()
                && $orderItemTransfer->getRelatedBundleItemIdentifierOrFail() === $productBundleItemTransfer->getRelatedBundleItemIdentifierOrFail()
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     * @param string $bundleItemIdentifier
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    protected function removeRelatedBundledItems(CartReorderTransfer $cartReorderTransfer, string $bundleItemIdentifier): CartReorderTransfer
    {
        $bundledItemIndexes = [];
        foreach ($cartReorderTransfer->getOrderItems() as $index => $itemTransfer) {
            if ($itemTransfer->getRelatedBundleItemIdentifier() === $bundleItemIdentifier) {
                $bundledItemIndexes[] = $index;
            }
        }

        foreach ($bundledItemIndexes as $index) {
            $cartReorderTransfer->getOrderItems()->offsetUnset($index);
        }

        return $cartReorderTransfer;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<string, array<int, \Generated\Shared\Transfer\ItemTransfer>>
     */
    protected function getBundledItemTransfersGroupedByProductBundleIdentifier(array $itemTransfers): array
    {
        $groupedBundledItemTransfers = [];
        foreach ($itemTransfers as $index => $itemTransfer) {
            if ($itemTransfer->getProductBundle()) {
                $bundleItemIdentifier = $itemTransfer->getProductBundleOrFail()->getBundleItemIdentifierOrFail();
                $groupedBundledItemTransfers[$bundleItemIdentifier][$index] = $itemTransfer;
            }
        }

        return $groupedBundledItemTransfers;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function getProductBundleItemsIndexedByProductBundleIdentifier(array $itemTransfers): array
    {
        $indexedProductBundleItems = [];
        foreach ($itemTransfers as $itemTransfer) {
            if ($itemTransfer->getProductBundle() === null) {
                continue;
            }

            $productBundleItemTransfer = $itemTransfer->getProductBundleOrFail();
            if (!isset($indexedProductBundleItems[$productBundleItemTransfer->getBundleItemIdentifierOrFail()])) {
                $indexedProductBundleItems[$productBundleItemTransfer->getBundleItemIdentifierOrFail()] = $productBundleItemTransfer;
            }
        }

        return $indexedProductBundleItems;
    }
}
