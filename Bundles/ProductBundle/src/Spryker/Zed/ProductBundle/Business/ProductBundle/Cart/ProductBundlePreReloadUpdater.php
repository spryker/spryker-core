<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Cart;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;

class ProductBundlePreReloadUpdater implements ProductBundlePreReloadUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function preReloadItems(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer = $this->removeBundledItems($quoteTransfer);
        $this->assignBundlesForExpanding($quoteTransfer);
        $this->removePreviouslyExpandedBundles($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function removeBundledItems(QuoteTransfer $quoteTransfer)
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
     *
     * @return void
     */
    protected function assignBundlesForExpanding(QuoteTransfer $quoteTransfer)
    {
        foreach ($quoteTransfer->getBundleItems() as $itemTransfer) {
            $quoteTransfer->addItem($itemTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function removePreviouslyExpandedBundles(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->setBundleItems(new ArrayObject());
    }
}
