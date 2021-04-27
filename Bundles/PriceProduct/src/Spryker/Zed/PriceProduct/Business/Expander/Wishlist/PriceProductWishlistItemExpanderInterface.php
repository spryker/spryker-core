<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Expander\Wishlist;

use Generated\Shared\Transfer\WishlistItemTransfer;

interface PriceProductWishlistItemExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function expandWishlistItem(WishlistItemTransfer $wishlistItemTransfer): WishlistItemTransfer;
}
