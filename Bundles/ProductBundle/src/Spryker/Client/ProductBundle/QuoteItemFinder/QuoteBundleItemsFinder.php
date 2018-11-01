<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBundle\QuoteItemFinder;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class QuoteBundleItemsFinder implements QuoteBundleItemsFinderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function findBundledItems(QuoteTransfer $quoteTransfer, $sku, $groupKey = null): array
    {
        $itemTransferCollection = null;
        if ($groupKey) {
            $itemTransferCollection = $this->findBundleItems($quoteTransfer, $groupKey);
        }
        if (!$itemTransferCollection) {
            $itemTransferCollection = [$this->findQuoteItem($quoteTransfer, $sku, $groupKey)];
        }

        return $itemTransferCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $groupKey
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function findBundleItems(QuoteTransfer $quoteTransfer, $groupKey): array
    {
        $itemTransferCollection = [];
        foreach ($quoteTransfer->getBundleItems() as $itemTransfer) {
            if ($itemTransfer->getGroupKey() === $groupKey) {
                $itemTransferCollection[] = $itemTransfer;
            }
        }

        return $itemTransferCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function findQuoteItem(QuoteTransfer $quoteTransfer, $sku, $groupKey = null): ?ItemTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (($itemTransfer->getSku() === $sku && $groupKey === null) ||
                $itemTransfer->getGroupKey() === $groupKey) {
                return $itemTransfer;
            }
        }

        return null;
    }
}
