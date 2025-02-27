<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartReorder\Business\Hydrator;

use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ItemTransfer;

class ItemHydrator implements ItemHydratorInterface
{
    /**
     * @param list<\Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderItemHydratorPluginInterface> $cartReorderItemHydratorPlugins
     */
    public function __construct(protected array $cartReorderItemHydratorPlugins)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function hydrate(CartReorderTransfer $cartReorderTransfer): CartReorderTransfer
    {
        $cartReorderTransfer = $this->executeCartReorderItemHydratorPlugins($cartReorderTransfer);

        return $this->hydrateLeftoverItems($cartReorderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    protected function hydrateLeftoverItems(CartReorderTransfer $cartReorderTransfer): CartReorderTransfer
    {
        foreach ($cartReorderTransfer->getOrderItems() as $index => $itemTransfer) {
            if ($this->isItemAlreadyReordered($cartReorderTransfer, $itemTransfer)) {
                continue;
            }

            $cartReorderTransfer->getReorderItems()->offsetSet(
                $index,
                (new ItemTransfer())
                    ->setSku($itemTransfer->getSkuOrFail())
                    ->setQuantity($itemTransfer->getQuantityOrFail())
                    ->setIdSalesOrderItem($itemTransfer->getIdSalesOrderItemOrFail()),
            );
        }

        $cartReorderTransfer->getReorderItems()->ksort();

        return $cartReorderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isItemAlreadyReordered(CartReorderTransfer $cartReorderTransfer, ItemTransfer $itemTransfer): bool
    {
        foreach ($cartReorderTransfer->getReorderItems() as $reorderItemTransfer) {
            if ($reorderItemTransfer->getIdSalesOrderItemOrFail() === $itemTransfer->getIdSalesOrderItemOrFail()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    protected function executeCartReorderItemHydratorPlugins(CartReorderTransfer $cartReorderTransfer): CartReorderTransfer
    {
        foreach ($this->cartReorderItemHydratorPlugins as $cartReorderItemHydratorPlugin) {
            $cartReorderTransfer = $cartReorderItemHydratorPlugin->hydrate($cartReorderTransfer);
        }

        return $cartReorderTransfer;
    }
}
