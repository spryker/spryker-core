<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Cart;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;

class ProductBundleCartPostSaveUpdate implements ProductBundleCartPostSaveUpdateInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function updateBundles(QuoteTransfer $quoteTransfer)
    {
        $itemTransfersIndexedByRelatedBundleItemIdentifier = $this->getItemTransfersIndexedByRelatedBundleItemIdentifier($quoteTransfer);

        $bundleItems = new ArrayObject();
        foreach ($quoteTransfer->getBundleItems() as $bundleItemTransfer) {
            if (!array_key_exists($bundleItemTransfer->getBundleItemIdentifier(), $itemTransfersIndexedByRelatedBundleItemIdentifier)) {
                continue;
            }

            $bundleItems->append($bundleItemTransfer);
        }

        $quoteTransfer->setBundleItems($bundleItems);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function getItemTransfersIndexedByRelatedBundleItemIdentifier(QuoteTransfer $quoteTransfer): array
    {
        $itemTransfersIndexedByRelatedBundleItemIdentifier = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getRelatedBundleItemIdentifier() === null) {
                continue;
            }

            $itemTransfersIndexedByRelatedBundleItemIdentifier[$itemTransfer->getRelatedBundleItemIdentifierOrFail()] = $itemTransfer;
        }

        return $itemTransfersIndexedByRelatedBundleItemIdentifier;
    }
}
