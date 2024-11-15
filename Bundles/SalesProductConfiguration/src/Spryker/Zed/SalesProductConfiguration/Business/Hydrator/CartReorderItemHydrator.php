<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConfiguration\Business\Hydrator;

use ArrayObject;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;

class CartReorderItemHydrator implements CartReorderItemHydratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function hydrate(CartReorderTransfer $cartReorderTransfer): CartReorderTransfer
    {
        $itemsWithProductConfiguration = $this->extractItemsWithProductConfiguration($cartReorderTransfer->getOrderItems());
        if ($itemsWithProductConfiguration === []) {
            return $cartReorderTransfer;
        }

        $reorderItemsIndexedByIdSalesOrder = $this->getItemTransfersIndexedByIdSalesOrder($cartReorderTransfer->getReorderItems());
        foreach ($itemsWithProductConfiguration as $index => $itemTransfer) {
            $reorderItemTransfer = $reorderItemsIndexedByIdSalesOrder[$itemTransfer->getIdSalesOrderItemOrFail()] ?? null;
            if (!$reorderItemTransfer) {
                $this->addReorderItem($cartReorderTransfer, $itemTransfer, $index);

                continue;
            }

            $this->addProductConfigurationItemInstance($itemTransfer, $reorderItemTransfer);
        }

        return $cartReorderTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function extractItemsWithProductConfiguration(ArrayObject $itemTransfers): array
    {
        $itemsWithProductConfiguration = [];
        foreach ($itemTransfers as $index => $itemTransfer) {
            if ($itemTransfer->getSalesOrderItemConfiguration() !== null) {
                $itemsWithProductConfiguration[$index] = $itemTransfer;
            }
        }

        return $itemsWithProductConfiguration;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function getItemTransfersIndexedByIdSalesOrder(ArrayObject $itemTransfers): array
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
            ->setQuantity($itemTransfer->getQuantityOrFail());
        $reorderItemTransfer = $this->addProductConfigurationItemInstance($itemTransfer, $reorderItemTransfer);

        $cartReorderTransfer->getReorderItems()->offsetSet($index, $reorderItemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $reorderItemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function addProductConfigurationItemInstance(ItemTransfer $itemTransfer, ItemTransfer $reorderItemTransfer): ItemTransfer
    {
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceTransfer())->fromArray(
            $itemTransfer->getSalesOrderItemConfigurationOrFail()->toArray(),
            true,
        );
        $productConfigurationInstanceTransfer->setIsComplete(false);

        return $reorderItemTransfer->setProductConfigurationInstance($productConfigurationInstanceTransfer);
    }
}
