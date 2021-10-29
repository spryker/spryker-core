<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationWishlist\Expander;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer;

interface ProductConfigurationWishlistMoveToCartExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionDiffTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer
     */
    public function expandWishlistMoveToCartRequestCollection(
        WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionTransfer,
        QuoteTransfer $quoteTransfer,
        WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionDiffTransfer
    ): WishlistMoveToCartRequestCollectionTransfer;
}
