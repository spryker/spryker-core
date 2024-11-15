<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantity\Business\Merger;

use ArrayObject;
use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\ProductQuantity\Business\Model\ProductQuantityReaderInterface;

class CartReorderItemMerger implements CartReorderItemMergerInterface
{
    /**
     * @var \Spryker\Zed\ProductQuantity\Business\Model\ProductQuantityReaderInterface
     */
    protected ProductQuantityReaderInterface $productQuantityReader;

    /**
     * @param \Spryker\Zed\ProductQuantity\Business\Model\ProductQuantityReaderInterface $productQuantityReader
     */
    public function __construct(ProductQuantityReaderInterface $productQuantityReader)
    {
        $this->productQuantityReader = $productQuantityReader;
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function mergeProductQuantityRestrictionItems(
        CartReorderRequestTransfer $cartReorderRequestTransfer,
        CartReorderTransfer $cartReorderTransfer
    ): CartReorderTransfer {
        $itemsWithProductQuantity = $this->extractOrderItemsWithProductQuantity(
            $cartReorderTransfer->getOrderOrFail()->getItems(),
        );
        if ($itemsWithProductQuantity === []) {
            return $cartReorderTransfer;
        }

        $filteredItemsWithProductQuantity = $this->filterOutProductQuantityItemsByCartReorderRequest(
            $cartReorderRequestTransfer,
            $itemsWithProductQuantity,
        );
        if ($filteredItemsWithProductQuantity === []) {
            return $cartReorderTransfer;
        }

        $salesOrderItemIdsGroupedByGroupKey = $this->getSalesOrderIdsGroupedByGroupKey($filteredItemsWithProductQuantity);
        $mergedItemsWithProductQuantity = $this->mergeItemTransfers($filteredItemsWithProductQuantity);

        foreach ($mergedItemsWithProductQuantity as $itemTransfer) {
            $cartReorderTransfer = $this->replaceOrderItemsWithMergedItem(
                $cartReorderTransfer,
                $itemTransfer,
                $salesOrderItemIdsGroupedByGroupKey,
            );
        }

        return $cartReorderTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return list<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function extractOrderItemsWithProductQuantity(ArrayObject $itemTransfers): array
    {
        $productConcreteSkus = $this->extractProductConcreteSkus($itemTransfers);
        $productQuantityTransfersIndexedBySku = $this->getProductQuantityTransfersIndexedBySku($productConcreteSkus);

        $orderItemsWithProductQuantity = [];
        foreach ($itemTransfers as $itemTransfer) {
            if (isset($productQuantityTransfersIndexedBySku[$itemTransfer->getSkuOrFail()])) {
                $orderItemsWithProductQuantity[] = $itemTransfer;
            }
        }

        return $orderItemsWithProductQuantity;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return list<string>
     */
    protected function extractProductConcreteSkus(ArrayObject $itemTransfers): array
    {
        $productConcreteSkus = [];
        foreach ($itemTransfers as $itemTransfer) {
            $productConcreteSkus[] = $itemTransfer->getSku();
        }

        return $productConcreteSkus;
    }

    /**
     * @param list<string> $productConcreteSkus
     *
     * @return array<string, \Generated\Shared\Transfer\ProductQuantityTransfer>
     */
    protected function getProductQuantityTransfersIndexedBySku(array $productConcreteSkus): array
    {
        $productQuantityTransfers = $this->productQuantityReader->findProductQuantityTransfersByProductSku($productConcreteSkus);

        $productQuantityTransfersIndexedBySku = [];
        foreach ($productQuantityTransfers as $productQuantityTransfer) {
            $sku = $productQuantityTransfer->getProductOrFail()->getSkuOrFail();
            $productQuantityTransfersIndexedBySku[$sku] = $productQuantityTransfer;
        }

        return $productQuantityTransfersIndexedBySku;
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemsWithProductQuantity
     *
     * @return list<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function filterOutProductQuantityItemsByCartReorderRequest(
        CartReorderRequestTransfer $cartReorderRequestTransfer,
        array $itemsWithProductQuantity
    ): array {
        $salesOrderItemIds = array_flip($cartReorderRequestTransfer->getSalesOrderItemIds());
        if ($salesOrderItemIds === []) {
            return $itemsWithProductQuantity;
        }

        $filteredItemTransfers = [];
        foreach ($itemsWithProductQuantity as $itemTransfer) {
            if (isset($salesOrderItemIds[$itemTransfer->getIdSalesOrderItemOrFail()])) {
                $filteredItemTransfers[] = $itemTransfer;
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
