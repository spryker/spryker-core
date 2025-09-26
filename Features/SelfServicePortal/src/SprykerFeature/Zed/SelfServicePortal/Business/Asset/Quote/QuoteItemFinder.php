<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Asset\Quote;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class QuoteItemFinder implements QuoteItemFinderInterface
{
    public function findItem(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer): ?ItemTransfer
    {
        foreach ($quoteTransfer->getItems() as $quoteItemTransfer) {
            if (
                ($quoteItemTransfer->getSku() === $itemTransfer->getSku() && $itemTransfer->getGroupKey() === null) ||
                $quoteItemTransfer->getGroupKey() === $itemTransfer->getGroupKey()
            ) {
                return $quoteItemTransfer;
            }
        }

        return null;
    }
}
