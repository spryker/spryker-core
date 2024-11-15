<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Filter;

use ArrayObject;
use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;

class ReorderBundleItemFilter implements ReorderBundleItemFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer>
     */
    public function filterReorderBundleItems(
        CartReorderRequestTransfer $cartReorderRequestTransfer,
        OrderTransfer $orderTransfer
    ): ArrayObject {
        $bundleItemTransfers = $this->filterOrderItemsByBundleItemIdentifiers($cartReorderRequestTransfer, $orderTransfer);
        $bundleItemTransfers = $this->expandBundleItemsWithIdSalesOrderItem($orderTransfer, $bundleItemTransfers);

        return new ArrayObject($bundleItemTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return list<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function filterOrderItemsByBundleItemIdentifiers(
        CartReorderRequestTransfer $cartReorderRequestTransfer,
        OrderTransfer $orderTransfer
    ): array {
        $bundleItemTransfers = [];
        foreach ($cartReorderRequestTransfer->getBundleItemIdentifiers() as $bundleItemIdentifier) {
            $bundleItemTransfer = $this->extractBundleItemTransferByBundleItemIdentifier($orderTransfer, $bundleItemIdentifier);
            if (!$bundleItemTransfer) {
                continue;
            }

            $bundleItemTransfers[] = $bundleItemTransfer;
        }

        return $bundleItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param string $bundleItemIdentifier
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function extractBundleItemTransferByBundleItemIdentifier(
        OrderTransfer $orderTransfer,
        string $bundleItemIdentifier
    ): ?ItemTransfer {
        foreach ($orderTransfer->getBundleItems() as $itemTransfer) {
            if ($itemTransfer->getBundleItemIdentifier() && $itemTransfer->getBundleItemIdentifierOrFail() === $bundleItemIdentifier) {
                return $itemTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $bundleItemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function expandBundleItemsWithIdSalesOrderItem(OrderTransfer $orderTransfer, array $bundleItemTransfers): array
    {
        $salesOrderItemIds = $this->getSalesOrderItemIdsIndexedByBundleItemIdentifier($orderTransfer);

        foreach ($bundleItemTransfers as $bundleItemTransfer) {
            $bundleItemIdentifier = $bundleItemTransfer->getBundleItemIdentifier();

            if ($bundleItemIdentifier) {
                $bundleItemTransfer->setIdSalesOrderItem($salesOrderItemIds[$bundleItemIdentifier] ?? null);
            }
        }

        return $bundleItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array<int>
     */
    protected function getSalesOrderItemIdsIndexedByBundleItemIdentifier(OrderTransfer $orderTransfer): array
    {
        $salesOrderItemIds = [];
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $relatedBundleItemIdentifier = $itemTransfer->getRelatedBundleItemIdentifier();
            if ($relatedBundleItemIdentifier && !isset($salesOrderItemIds[$relatedBundleItemIdentifier])) {
                $salesOrderItemIds[$relatedBundleItemIdentifier] = $itemTransfer->getIdSalesOrderItemOrFail();
            }
        }

        return $salesOrderItemIds;
    }
}
