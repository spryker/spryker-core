<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Business\Hydrator;

use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ItemTransfer;

class CartReorderItemHydrator implements CartReorderItemHydratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function hydrateOriginalSalesOrderItemGroupKeys(
        CartReorderTransfer $cartReorderTransfer
    ): CartReorderTransfer {
        $this->assertRequiredFields($cartReorderTransfer);
        $reorderItemsIndexedByIdSalesOrderItem = $this->getReorderItemsIndexedByIdSalesOrderItem($cartReorderTransfer);

        foreach ($cartReorderTransfer->getOrderItems() as $index => $itemTransfer) {
            $reorderItem = $reorderItemsIndexedByIdSalesOrderItem[$itemTransfer->getIdSalesOrderItemOrFail()] ?? null;

            if ($reorderItem === null) {
                $this->addReorderItem($cartReorderTransfer, $itemTransfer, $index);

                continue;
            }

            $reorderItem->setOriginalSalesOrderItemGroupKey($itemTransfer->getGroupKeyOrFail());
        }

        return $cartReorderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $index
     *
     * @return void
     */
    protected function addReorderItem(
        CartReorderTransfer $cartReorderTransfer,
        ItemTransfer $itemTransfer,
        int $index
    ): void {
        $reorderItemTransfer = (new ItemTransfer())
            ->setIdSalesOrderItem($itemTransfer->getIdSalesOrderItemOrFail())
            ->setSku($itemTransfer->getSkuOrFail())
            ->setQuantity($itemTransfer->getQuantityOrFail())
            ->setOriginalSalesOrderItemGroupKey($itemTransfer->getGroupKeyOrFail());

        $cartReorderTransfer->getReorderItems()->offsetSet($index, $reorderItemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function getReorderItemsIndexedByIdSalesOrderItem(CartReorderTransfer $cartReorderTransfer): array
    {
        $reorderItemsIndexedByIdSalesOrderItem = [];
        foreach ($cartReorderTransfer->getReorderItems() as $itemTransfer) {
            $reorderItemsIndexedByIdSalesOrderItem[$itemTransfer->getIdSalesOrderItemOrFail()] = $itemTransfer;
        }

        return $reorderItemsIndexedByIdSalesOrderItem;
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return void
     */
    protected function assertRequiredFields(CartReorderTransfer $cartReorderTransfer): void
    {
        foreach ($cartReorderTransfer->getOrderItems() as $itemTransfer) {
            $itemTransfer
                ->requireSku()
                ->requireQuantity()
                ->requireIdSalesOrderItem()
                ->requireGroupKey();
        }

        foreach ($cartReorderTransfer->getReorderItems() as $itemTransfer) {
            $itemTransfer->requireIdSalesOrderItem();
        }
    }
}
