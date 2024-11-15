<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProduct\Business\Hydrator;

use ArrayObject;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ItemTransfer;

class CartReorderItemHydrator implements CartReorderItemHydratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function hydrate(CartReorderTransfer $cartReorderTransfer): CartReorderTransfer
    {
        $itemsWithMerchantReference = $this->extractItemsWithMerchantReference($cartReorderTransfer->getOrderItems());
        if ($itemsWithMerchantReference === []) {
            return $cartReorderTransfer;
        }

        $reorderItemsIndexedByIdSalesOrder = $this->getItemsIndexedByIdSalesOrder($cartReorderTransfer->getReorderItems());
        foreach ($itemsWithMerchantReference as $index => $itemTransfer) {
            $reorderItemTransfer = $reorderItemsIndexedByIdSalesOrder[$itemTransfer->getIdSalesOrderItemOrFail()] ?? null;
            if (!$reorderItemTransfer) {
                $this->addReorderItem($cartReorderTransfer, $itemTransfer, $index);

                continue;
            }

            $reorderItemTransfer->setMerchantReference($itemTransfer->getMerchantReferenceOrFail());
        }

        return $cartReorderTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function extractItemsWithMerchantReference(ArrayObject $itemTransfers): array
    {
        $itemsWithMerchantReference = [];
        foreach ($itemTransfers as $index => $itemTransfer) {
            if ($itemTransfer->getMerchantReference()) {
                $itemsWithMerchantReference[$index] = $itemTransfer;
            }
        }

        return $itemsWithMerchantReference;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function getItemsIndexedByIdSalesOrder(ArrayObject $itemTransfers): array
    {
        $indexedItemTransfers = [];
        foreach ($itemTransfers as $itemTransfer) {
            $indexedItemTransfers[$itemTransfer->getIdSalesOrderItemOrFail()] = $itemTransfer;
        }

        return $indexedItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $index
     *
     * @return void
     */
    protected function addReorderItem(CartReorderTransfer $cartReorderTransfer, ItemTransfer $itemTransfer, int $index): void
    {
        $reorderItemTransfer = (new ItemTransfer())
            ->setIdSalesOrderItem($itemTransfer->getIdSalesOrderItemOrFail())
            ->setSku($itemTransfer->getSkuOrFail())
            ->setQuantity($itemTransfer->getQuantityOrFail())
            ->setMerchantReference($itemTransfer->getMerchantReferenceOrFail());

        $cartReorderTransfer->getReorderItems()->offsetSet($index, $reorderItemTransfer);
    }
}
