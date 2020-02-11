<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleCart\Business\Checker;

use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class ConfiguredBundleQuantityChecker implements ConfiguredBundleQuantityCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function checkConfiguredBundleQuantity(QuoteTransfer $quoteTransfer): bool
    {
        $configuredBundleTransfers = $this->getConfiguredBundlesFromQuote($quoteTransfer);

        if (!$configuredBundleTransfers) {
            return true;
        }

        foreach ($configuredBundleTransfers as $configuredBundleTransfer) {
            if (!$this->checkConfiguredBundleQuantityCorrectness($configuredBundleTransfer)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundleTransfer[]
     */
    protected function getConfiguredBundlesFromQuote(QuoteTransfer $quoteTransfer): array
    {
        $configuredBundleTransfers = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($this->isConfiguredBundleItem($itemTransfer)) {
                $configuredBundleTransfers = $this->mapConfiguredBundle($itemTransfer, $configuredBundleTransfers);
            }
        }

        return $configuredBundleTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ConfiguredBundleTransfer[] $configuredBundleTransfers
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundleTransfer[]
     */
    protected function mapConfiguredBundle(ItemTransfer $itemTransfer, array $configuredBundleTransfers): array
    {
        $configuredBundleItemTransfer = $itemTransfer->getConfiguredBundleItem();
        $configuredBundleTransfer = $itemTransfer->getConfiguredBundle();

        $configuredBundleItemTransfer
            ->requireQuantityPerSlot();

        $configuredBundleTransfer
            ->requireGroupKey()
            ->requireQuantity();

        if (!isset($configuredBundleTransfers[$configuredBundleTransfer->getGroupKey()])) {
            $configuredBundleTransfers[$configuredBundleTransfer->getGroupKey()] = (new ConfiguredBundleTransfer())
                ->fromArray($configuredBundleTransfer->toArray());
        }

        $configuredBundleTransfers[$configuredBundleTransfer->getGroupKey()]->addItem($itemTransfer);

        return $configuredBundleTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleTransfer $configuredBundleTransfer
     *
     * @return bool
     */
    protected function checkConfiguredBundleQuantityCorrectness(ConfiguredBundleTransfer $configuredBundleTransfer): bool
    {
        $bundleQuantity = $configuredBundleTransfer->getQuantity();

        foreach ($configuredBundleTransfer->getItems() as $itemTransfer) {
            if ($bundleQuantity !== $itemTransfer->getConfiguredBundle()->getQuantity()) {
                return false;
            }

            $itemQuantity = (int)$itemTransfer->getConfiguredBundle()->getQuantity() * $itemTransfer->getConfiguredBundleItem()->getQuantityPerSlot();

            if ($itemQuantity !== $itemTransfer->getQuantity()) {
                return false;
            }
        }

        return true;
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
