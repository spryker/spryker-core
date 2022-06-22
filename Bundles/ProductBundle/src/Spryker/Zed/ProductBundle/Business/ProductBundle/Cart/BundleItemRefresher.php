<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Cart;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class BundleItemRefresher implements BundleRefresherInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function refreshBundlesWithUnitedItemsToBeInSyncWithQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $bundleItems = new ArrayObject();
        foreach ($quoteTransfer->getBundleItems() as $bundleItemTransfer) {
            $quoteItemTransfer = $this->findFirstQuoteItemByBundleIdentifier(
                $quoteTransfer,
                $bundleItemTransfer->getBundleItemIdentifierOrFail(),
            );
            if ($quoteItemTransfer) {
                $bundleItemTransfer->setQuantity($quoteItemTransfer->getQuantity());
                $bundleItems->append($bundleItemTransfer);
            }
        }

        $quoteTransfer->setBundleItems($bundleItems);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $bundleItemIdentifier
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function findFirstQuoteItemByBundleIdentifier(QuoteTransfer $quoteTransfer, string $bundleItemIdentifier): ?ItemTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($bundleItemIdentifier === $itemTransfer->getRelatedBundleItemIdentifier()) {
                return $itemTransfer;
            }
        }

        return null;
    }
}
