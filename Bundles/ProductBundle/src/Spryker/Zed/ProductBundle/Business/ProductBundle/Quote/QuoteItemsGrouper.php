<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Quote;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;

class QuoteItemsGrouper implements QuoteItemsGrouperInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function extractQuoteItems(QuoteTransfer $quoteTransfer): array
    {
        $quoteTransfer = (new QuoteTransfer())->fromArray($quoteTransfer->modifiedToArray(), true);
        $quoteTransfer = $this->removeBundledItems($quoteTransfer);

        return array_merge((array)$quoteTransfer->getItems(), $this->findBundleItems($quoteTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function findBundleItems(QuoteTransfer $quoteTransfer): array
    {
        $itemTransferCollection = [];
        foreach ($quoteTransfer->getBundleItems() as $itemTransfer) {
            if (!isset($itemTransferCollection[$itemTransfer->getGroupKey()])) {
                $itemTransferCollection[$itemTransfer->getGroupKey()] = $itemTransfer
                    ->setQuantity($this->getBundledProductTotalQuantity($quoteTransfer, $itemTransfer->getGroupKey()));
            }
        }

        return array_values($itemTransferCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function removeBundledItems(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $items = new ArrayObject();
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getRelatedBundleItemIdentifier()) {
                continue;
            }
            $items[] = $itemTransfer;
        }
        $quoteTransfer->setItems($items);

        return $quoteTransfer;
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
