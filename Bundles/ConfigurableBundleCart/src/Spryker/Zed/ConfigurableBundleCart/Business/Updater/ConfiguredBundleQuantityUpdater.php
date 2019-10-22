<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleCart\Business\Updater;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class ConfiguredBundleQuantityUpdater implements ConfiguredBundleQuantityUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function updateConfiguredBundleQuantity(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$this->isConfiguredBundleItem($itemTransfer)) {
                continue;
            }

            $itemTransfer->getConfiguredBundleItem()
                ->requireQuantityPerSlot();

            $quantity = (int)($itemTransfer->getQuantity() / $itemTransfer->getConfiguredBundleItem()->getQuantityPerSlot());
            $itemTransfer->getConfiguredBundle()->setQuantity($quantity);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function updateConfiguredBundleQuantityPerSlot(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$this->isConfiguredBundleItem($itemTransfer)) {
                continue;
            }

            $itemTransfer->getConfiguredBundle()
                ->requireQuantity();

            $quantityPerSlot = (int)($itemTransfer->getQuantity() / $itemTransfer->getConfiguredBundle()->getQuantity());
            $itemTransfer->getConfiguredBundleItem()->setQuantityPerSlot($quantityPerSlot);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isConfiguredBundleItem(ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getConfiguredBundleItem() && $itemTransfer->getConfiguredBundle();
    }
}
