<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\PersistentCart;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class BundleItemReplacer implements BundleItemReplacerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function replaceBundlesWithUnitedItems(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        $itemTransfers = [];

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $bundledItemTransfers = $this->getBundledItems(
                $cartChangeTransfer->getQuoteOrFail(),
                $itemTransfer->getGroupKeyOrFail(),
                $itemTransfer->getQuantityOrFail(),
            );

            if (count($bundledItemTransfers)) {
                $itemTransfers = array_merge($itemTransfers, $bundledItemTransfers);

                continue;
            }

            $itemTransfers[] = $itemTransfer;
        }

        return $cartChangeTransfer->setItems(new ArrayObject($itemTransfers));
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $groupKey
     * @param int $numberOfBundlesToRemove
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function getBundledItems(QuoteTransfer $quoteTransfer, string $groupKey, int $numberOfBundlesToRemove): array
    {
        if (!$numberOfBundlesToRemove) {
            $numberOfBundlesToRemove = $this->getBundledProductTotalQuantity($quoteTransfer, $groupKey);
            if (!$numberOfBundlesToRemove) {
                return [];
            }
        }

        foreach ($quoteTransfer->getBundleItems() as $bundleItemTransfer) {
            if ($bundleItemTransfer->getGroupKey() !== $groupKey) {
                continue;
            }

            return $this->filterBundledItemsByRelatedBundleItemIdentifier(
                $quoteTransfer,
                $bundleItemTransfer,
                $numberOfBundlesToRemove,
            );
        }

        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $bundleItemTransfer
     * @param int $numberOfBundlesToRemove
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function filterBundledItemsByRelatedBundleItemIdentifier(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $bundleItemTransfer,
        int $numberOfBundlesToRemove
    ): array {
        $bundledItems = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getRelatedBundleItemIdentifier() !== $bundleItemTransfer->getBundleItemIdentifier()) {
                continue;
            }

            $modifiedItemTransfer = (new ItemTransfer())->fromArray($itemTransfer->toArray(), true);
            $modifiedItemTransfer->setQuantity($numberOfBundlesToRemove);
            $bundledItems[] = $modifiedItemTransfer;
        }

        return $bundledItems;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $groupKey
     *
     * @return int
     */
    protected function getBundledProductTotalQuantity(QuoteTransfer $quoteTransfer, string $groupKey): int
    {
        $bundleItemQuantity = 0;

        foreach ($quoteTransfer->getBundleItems() as $bundleItemTransfer) {
            if ($bundleItemTransfer->getGroupKey() !== $groupKey) {
                continue;
            }

            $bundleItemQuantity += $bundleItemTransfer->getQuantity();
        }

        return $bundleItemQuantity;
    }
}
