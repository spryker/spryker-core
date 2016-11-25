<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle;

use Generated\Shared\Transfer\QuoteTransfer;

class ProductBundlePostSaveUpdate
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function updateBundles(QuoteTransfer $quoteTransfer)
    {
        $bundleItems = new \ArrayObject();
        foreach ($quoteTransfer->getBundleProducts() as $bundleItemTransfer) {
            foreach ($quoteTransfer->getItems() as $itemTransfer) {
                if ($bundleItemTransfer->getBundleItemIdentifier() == $itemTransfer->getRelatedBundleItemIdentifier()) {
                    $bundleItems->append($bundleItemTransfer);
                    break;
                }
            }
        }

        $quoteTransfer->setBundleProducts($bundleItems);


        return $quoteTransfer;

    }
}
