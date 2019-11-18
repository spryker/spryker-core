<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleCart\Business\Expander;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;

class ConfiguredBundleQuantityExpander implements ConfiguredBundleQuantityExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandConfiguredBundleItemsWithQuantityPerSlot(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$this->isExpandWithQuantityPerSlotNeeded($itemTransfer)) {
                continue;
            }

            $this->expandItemTransferWithQuantityPerSlot($itemTransfer);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isExpandWithQuantityPerSlotNeeded(ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getConfiguredBundle()
            && $itemTransfer->getConfiguredBundleItem()
            && !$itemTransfer->getConfiguredBundleItem()->getQuantityPerSlot();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function expandItemTransferWithQuantityPerSlot(ItemTransfer $itemTransfer): void
    {
        $itemTransfer
            ->requireQuantity()
            ->requireConfiguredBundleItem()
            ->requireConfiguredBundle()
            ->getConfiguredBundle()
                ->requireQuantity();

        $quantityPerSlot = (int)($itemTransfer->getQuantity() / $itemTransfer->getConfiguredBundle()->getQuantity());
        $itemTransfer->getConfiguredBundleItem()->setQuantityPerSlot($quantityPerSlot);
    }
}
