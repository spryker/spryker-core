<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Merger;

use ArrayObject;
use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\ProductMeasurementUnit\Business\Extractor\ProductMeasurementUnitItemExtractorInterface;

class CartReorderItemMerger implements CartReorderItemMergerInterface
{
    /**
     * @var \Spryker\Zed\ProductMeasurementUnit\Business\Extractor\ProductMeasurementUnitItemExtractorInterface
     */
    protected ProductMeasurementUnitItemExtractorInterface $productMeasurementUnitItemExtractor;

    /**
     * @param \Spryker\Zed\ProductMeasurementUnit\Business\Extractor\ProductMeasurementUnitItemExtractorInterface $productMeasurementUnitItemExtractor
     */
    public function __construct(ProductMeasurementUnitItemExtractorInterface $productMeasurementUnitItemExtractor)
    {
        $this->productMeasurementUnitItemExtractor = $productMeasurementUnitItemExtractor;
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function mergeProductMeasurementUnitItems(
        CartReorderRequestTransfer $cartReorderRequestTransfer,
        CartReorderTransfer $cartReorderTransfer
    ): CartReorderTransfer {
        $itemsWithQuantitySalesUnit = $this->productMeasurementUnitItemExtractor->extractItemsWithQuantitySalesUnit(
            $cartReorderTransfer->getOrderOrFail()->getItems(),
        );
        if ($itemsWithQuantitySalesUnit === []) {
            return $cartReorderTransfer;
        }

        $filteredItemsWithQuantitySalesUnit = $this->filterOutMeasurementUnitItemsByCartReorderRequest(
            $cartReorderRequestTransfer,
            $itemsWithQuantitySalesUnit,
        );
        if ($filteredItemsWithQuantitySalesUnit === []) {
            return $cartReorderTransfer;
        }

        $salesOrderItemIdsGroupedByGroupKey = $this->getSalesOrderIdsGroupedByGroupKey($filteredItemsWithQuantitySalesUnit);
        $mergedItemsWithQuantitySalesUnit = $this->mergeItemTransfers($filteredItemsWithQuantitySalesUnit);

        foreach ($mergedItemsWithQuantitySalesUnit as $itemTransfer) {
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
    protected function filterOutMeasurementUnitItemsByCartReorderRequest(
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
