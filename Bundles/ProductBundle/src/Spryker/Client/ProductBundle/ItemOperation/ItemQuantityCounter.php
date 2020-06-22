<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBundle\ItemOperation;

use Generated\Shared\Transfer\QuoteTransfer;

class ItemQuantityCounter implements ItemQuantityCounterInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    public function getItemCount(QuoteTransfer $quoteTransfer): int
    {
        $quantity = 0;
        foreach ($quoteTransfer->getBundleItems() as $bundleItemTransfer) {
            $quantity += $bundleItemTransfer->getQuantity();
        }

        foreach ($quoteTransfer->getItems() as $key => $itemTransfer) {
            if ($itemTransfer->getRelatedBundleItemIdentifier()) {
                continue;
            }

            $quantity += $itemTransfer->getQuantity();
        }

        return $quantity;
    }
}
