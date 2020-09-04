<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundlesCartsRestApi\Business\Checker;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class BundleItemChecker implements BundleItemCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isBundleItemInQuote(
        CartItemRequestTransfer $cartItemRequestTransfer,
        QuoteTransfer $quoteTransfer
    ): bool {
        if (!$quoteTransfer->getBundleItems()->count()) {
            return false;
        }

        foreach ($quoteTransfer->getBundleItems() as $itemTransfer) {
            if ($itemTransfer->getGroupKey() === $cartItemRequestTransfer->getGroupKey()) {
                return true;
            }
        }

        return false;
    }
}
