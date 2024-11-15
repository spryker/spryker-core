<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleNote\Business\Hydrator;

use ArrayObject;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ConfiguredBundleTransfer;
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
        $itemsWithConfigurableBundleNote = $this->extractItemsWithConfiguredBundleNote(
            $cartReorderTransfer->getOrderItems(),
        );
        if ($itemsWithConfigurableBundleNote === []) {
            return $cartReorderTransfer;
        }

        $reorderItemsIndexedByIdSalesOrder = $this->getItemTransfersIndexedByIdSalesOrder($cartReorderTransfer->getReorderItems());
        foreach ($itemsWithConfigurableBundleNote as $index => $itemTransfer) {
            $reorderItemTransfer = $reorderItemsIndexedByIdSalesOrder[$itemTransfer->getIdSalesOrderItemOrFail()] ?? null;
            if (!$reorderItemTransfer) {
                $this->addReorderItem($cartReorderTransfer, $itemTransfer, $index);

                continue;
            }

            $this->addConfiguredBundleNote($itemTransfer, $reorderItemTransfer);
        }

        return $cartReorderTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function extractItemsWithConfiguredBundleNote(ArrayObject $itemTransfers): array
    {
        $itemsWithConfigurableBundle = [];
        foreach ($itemTransfers as $index => $itemTransfer) {
            if ($itemTransfer->getSalesOrderConfiguredBundle() && $itemTransfer->getSalesOrderConfiguredBundleOrFail()->getNote()) {
                $itemsWithConfigurableBundle[$index] = $itemTransfer;
            }
        }

        return $itemsWithConfigurableBundle;
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
    protected function addReorderItem(
        CartReorderTransfer $cartReorderTransfer,
        ItemTransfer $itemTransfer,
        int $index
    ): void {
        $reorderItemTransfer = (new ItemTransfer())
            ->setIdSalesOrderItem($itemTransfer->getIdSalesOrderItemOrFail())
            ->setSku($itemTransfer->getSkuOrFail())
            ->setQuantity($itemTransfer->getQuantityOrFail());
        $reorderItemTransfer = $this->addConfiguredBundleNote($itemTransfer, $reorderItemTransfer);

        $cartReorderTransfer->getReorderItems()->offsetSet($index, $reorderItemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $reorderItemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function addConfiguredBundleNote(ItemTransfer $itemTransfer, ItemTransfer $reorderItemTransfer): ItemTransfer
    {
        $configuredBundleTransfer = $reorderItemTransfer->getConfiguredBundle() ?? new ConfiguredBundleTransfer();
        $configuredBundleTransfer->setNote($itemTransfer->getSalesOrderConfiguredBundleOrFail()->getNoteOrFail());

        return $reorderItemTransfer->setConfiguredBundle($configuredBundleTransfer);
    }
}
