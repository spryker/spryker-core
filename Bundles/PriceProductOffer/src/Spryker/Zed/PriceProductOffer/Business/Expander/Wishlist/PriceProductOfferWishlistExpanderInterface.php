<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Business\Expander\Wishlist;

use Generated\Shared\Transfer\WishlistItemTransfer;

interface PriceProductOfferWishlistExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function expandWishlistItemWithPrices(WishlistItemTransfer $wishlistItemTransfer): WishlistItemTransfer;
}
