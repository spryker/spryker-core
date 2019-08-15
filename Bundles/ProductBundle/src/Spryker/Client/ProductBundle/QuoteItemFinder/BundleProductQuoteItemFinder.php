<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBundle\QuoteItemFinder;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class BundleProductQuoteItemFinder implements BundleProductQuoteItemFinderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    public function findItem(QuoteTransfer $quoteTransfer, $sku, $groupKey = null): ?ItemTransfer
    {
        $itemTransfer = $this->findBundleItem($quoteTransfer, $sku, $groupKey);
        if (!$itemTransfer) {
            $itemTransfer = $this->findQuoteItem($quoteTransfer, $sku, $groupKey);
        }

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function findBundleItem(QuoteTransfer $quoteTransfer, string $sku, ?string $groupKey = null): ?ItemTransfer
    {
        foreach ($quoteTransfer->getBundleItems() as $itemTransfer) {
            if ($this->checkItem($itemTransfer, $sku, $groupKey)) {
                $itemTransfer = clone $itemTransfer;
                $itemTransfer->setQuantity($this->getBundledProductTotalQuantity($quoteTransfer, $itemTransfer->getGroupKey()));

                return $itemTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string|null $groupKey
     *
     * @return int
     */
    protected function getBundledProductTotalQuantity(QuoteTransfer $quoteTransfer, ?string $groupKey): int
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

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function findQuoteItem(QuoteTransfer $quoteTransfer, string $sku, ?string $groupKey = null): ?ItemTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($this->checkItem($itemTransfer, $sku, $groupKey)) {
                return $itemTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return bool
     */
    protected function checkItem(ItemTransfer $itemTransfer, string $sku, ?string $groupKey = null): bool
    {
        return ($itemTransfer->getSku() === $sku && $groupKey === null) ||
            $itemTransfer->getGroupKey() === $groupKey;
    }
}
