<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\CartNote;

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
    public function findItems(QuoteTransfer $quoteTransfer, $sku, $groupKey = null): array
    {
        $itemTransferCollection = null;
        if ($groupKey) {
            $itemTransferCollection = $this->findBundleItems($quoteTransfer, $groupKey);
        }
        if (!empty($itemTransferCollection)) {
            return $itemTransferCollection;
        }

        return $this->findQuoteItems($quoteTransfer, $sku, $groupKey);
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
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function findQuoteItems(QuoteTransfer $quoteTransfer, $sku, $groupKey = null): array
    {
        $itemTransferCollection = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (($itemTransfer->getSku() === $sku && $groupKey === null) ||
                $itemTransfer->getGroupKey() === $groupKey) {
                $itemTransferCollection[] = $itemTransfer;
            }
        }

        return $itemTransferCollection;
    }
}
