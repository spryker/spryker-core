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
            if (!$this->isItemConfiguredBundleAndQuantityNotSet($itemTransfer)) {
                continue;
            }

            $itemTransfer->getConfiguredBundleItem()->setQuantityPerSlot(
                $this->getCalculatedConfiguredBundleItemQuantityPerSlot($itemTransfer)
            );
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isItemConfiguredBundleAndQuantityNotSet(ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getConfiguredBundle()
            && $itemTransfer->getConfiguredBundleItem()
            && !$itemTransfer->getConfiguredBundleItem()->getQuantityPerSlot();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int
     */
    protected function getCalculatedConfiguredBundleItemQuantityPerSlot(ItemTransfer $itemTransfer): int
    {
        $itemTransfer
            ->requireQuantity()
            ->requireConfiguredBundleItem()
            ->requireConfiguredBundle()
            ->getConfiguredBundle()
                ->requireQuantity();

        return (int)($itemTransfer->getQuantity() / $itemTransfer->getConfiguredBundle()->getQuantity());
    }
}
