<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Quote;

use ArrayObject;
use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class QuoteItemsGrouper implements QuoteItemsGrouperInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    public function extractQuoteItems(QuoteTransfer $quoteTransfer): ItemCollectionTransfer
    {
        $itemCollectionTransfer = new ItemCollectionTransfer();
        $itemCollectionTransfer->setItems($quoteTransfer->getItems());

        $itemCollectionTransfer = $this->filterBundledItems($itemCollectionTransfer);
        $itemCollectionTransfer = $this->addBundleItems($itemCollectionTransfer, $quoteTransfer);

        return $itemCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemCollectionTransfer $itemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    protected function filterBundledItems(ItemCollectionTransfer $itemCollectionTransfer): ItemCollectionTransfer
    {
        $items = new ArrayObject();
        foreach ($itemCollectionTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getRelatedBundleItemIdentifier()) {
                continue;
            }
            $items->append($itemTransfer);
        }
        $itemCollectionTransfer->setItems($items);

        return $itemCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemCollectionTransfer $itemCollectionTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    protected function addBundleItems(ItemCollectionTransfer $itemCollectionTransfer, QuoteTransfer $quoteTransfer): ItemCollectionTransfer
    {
        $itemsProcessedIndex = [];
        foreach ($quoteTransfer->getBundleItems() as $itemTransfer) {
            if (!isset($itemsProcessedIndex[$itemTransfer->getGroupKey()])) {
                $itemsProcessedIndex[$itemTransfer->getGroupKey()] = true;
                $itemTransfer->setQuantity($this->getBundledProductTotalQuantity($quoteTransfer, $itemTransfer->getGroupKey()));
                $itemCollectionTransfer->addItem($itemTransfer);
            }
        }

        return $itemCollectionTransfer;
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
