<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Merger;

use ArrayObject;
use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\ProductPackagingUnit\Business\Extractor\ProductPackagingUnitItemExtractorInterface;

class CartReorderItemMerger implements CartReorderItemMergerInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Business\Extractor\ProductPackagingUnitItemExtractorInterface
     */
    protected ProductPackagingUnitItemExtractorInterface $productPackagingUnitItemExtractor;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Business\Extractor\ProductPackagingUnitItemExtractorInterface $productPackagingUnitItemExtractor
     */
    public function __construct(ProductPackagingUnitItemExtractorInterface $productPackagingUnitItemExtractor)
    {
        $this->productPackagingUnitItemExtractor = $productPackagingUnitItemExtractor;
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function mergeProductPackagingUnitItems(
        CartReorderRequestTransfer $cartReorderRequestTransfer,
        CartReorderTransfer $cartReorderTransfer
    ): CartReorderTransfer {
        $itemsWithAmountSalesUnit = $this->productPackagingUnitItemExtractor->extractItemsWithAmountSalesUnit(
            $cartReorderTransfer->getOrderOrFail()->getItems(),
        );
        if ($itemsWithAmountSalesUnit === []) {
            return $cartReorderTransfer;
        }

        $filteredItemsWithAmountSalesUnit = $this->filterOutPackagingUnitItemsByCartReorderRequest(
            $cartReorderRequestTransfer,
            $itemsWithAmountSalesUnit,
        );
        if ($filteredItemsWithAmountSalesUnit === []) {
            return $cartReorderTransfer;
        }

        $salesOrderItemIdsGroupedByGroupKey = $this->getSalesOrderIdsGroupedByGroupKey($filteredItemsWithAmountSalesUnit);
        $mergedItemsWithAmountSalesUnit = $this->mergeItemTransfers($filteredItemsWithAmountSalesUnit);

        foreach ($mergedItemsWithAmountSalesUnit as $itemTransfer) {
            $cartReorderTransfer = $this->replaceOrderItemsWithMergedItem(
                $cartReorderTransfer,
                $itemTransfer,
                $salesOrderItemIdsGroupedByGroupKey,
            );
        }

        return $cartReorderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     * @param array<int, \Generated\Shared\Transfer\ItemTransfer> $itemsWithQuantitySalesUnit
     *
     * @return array<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function filterOutPackagingUnitItemsByCartReorderRequest(
        CartReorderRequestTransfer $cartReorderRequestTransfer,
        array $itemsWithQuantitySalesUnit
    ): array {
        $salesOrderItemIds = array_flip($cartReorderRequestTransfer->getSalesOrderItemIds());
        if ($salesOrderItemIds === []) {
            return $itemsWithQuantitySalesUnit;
        }

        $filteredItemTransfers = [];
        foreach ($itemsWithQuantitySalesUnit as $index => $itemTransfer) {
            if (isset($salesOrderItemIds[$itemTransfer->getIdSalesOrderItemOrFail()])) {
                $filteredItemTransfers[$index] = $itemTransfer;
            }
        }

        return $filteredItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $mergedItemTransfer
     * @param array<string, list<int>> $salesOrderItemIdsGroupedByGroupKey
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    protected function replaceOrderItemsWithMergedItem(
        CartReorderTransfer $cartReorderTransfer,
        ItemTransfer $mergedItemTransfer,
        array $salesOrderItemIdsGroupedByGroupKey
    ): CartReorderTransfer {
        $salesOrderItemIds = $salesOrderItemIdsGroupedByGroupKey[$mergedItemTransfer->getGroupKeyOrFail()];
        $orderItemIndexes = $this->getOrderItemIndexesBySalesOrderItemIds($cartReorderTransfer->getOrderItems(), $salesOrderItemIds);

        $firstOrderItemIndex = array_shift($orderItemIndexes);
        $reorderItemTransfer = $cartReorderTransfer->getOrderItems()->offsetGet($firstOrderItemIndex);

        $reorderItemTransfer->setQuantity($mergedItemTransfer->getQuantityOrFail());
        $reorderItemTransfer->setAmount($mergedItemTransfer->getAmountOrFail());

        foreach ($orderItemIndexes as $orderItemIndex) {
            $cartReorderTransfer->getOrderItems()->offsetUnset($orderItemIndex);
        }

        return $cartReorderTransfer;
    }

    /**
     * @param array<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function mergeItemTransfers(array $itemTransfers): array
    {
        $mergedItems = [];
        foreach ($itemTransfers as $itemTransfer) {
            $groupKey = $itemTransfer->getGroupKeyOrFail();
            if (!isset($mergedItems[$groupKey])) {
                $mergedItems[$groupKey] = (new ItemTransfer())->fromArray($itemTransfer->toArray());

                continue;
            }

            $mergedItems[$groupKey]->setQuantity(
                $mergedItems[$groupKey]->getQuantityOrFail() + $itemTransfer->getQuantityOrFail(),
            );
            $amount = $mergedItems[$groupKey]->getAmount()->add($itemTransfer->getAmount());
            $mergedItems[$groupKey]->setAmount($amount);
        }

        return $mergedItems;
    }

    /**
     * @param array<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<string, list<int>>
     */
    protected function getSalesOrderIdsGroupedByGroupKey(array $itemTransfers): array
    {
        $groupedSalesOrderItemIds = [];
        foreach ($itemTransfers as $itemTransfer) {
            $groupedSalesOrderItemIds[$itemTransfer->getGroupKeyOrFail()][] = $itemTransfer->getIdSalesOrderItemOrFail();
        }

        return $groupedSalesOrderItemIds;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $orderItemTransfers
     * @param list<int> $salesOrderItemIds
     *
     * @return array<int, int>
     */
    protected function getOrderItemIndexesBySalesOrderItemIds(ArrayObject $orderItemTransfers, array $salesOrderItemIds): array
    {
        $salesOrderItemIds = array_flip($salesOrderItemIds);
        $orderItemIndexes = [];
        foreach ($orderItemTransfers as $index => $orderItemTransfer) {
            if (isset($salesOrderItemIds[$orderItemTransfer->getIdSalesOrderItemOrFail()])) {
                $orderItemIndexes[] = $index;
            }
        }

        return $orderItemIndexes;
    }
}
