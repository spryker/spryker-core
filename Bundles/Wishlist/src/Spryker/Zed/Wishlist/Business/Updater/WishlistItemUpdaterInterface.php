<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Business\Updater;

use Generated\Shared\Transfer\WishlistItemResponseTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;

interface WishlistItemUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemResponseTransfer
     */
    public function updateWishlistItem(WishlistItemTransfer $wishlistItemTransfer): WishlistItemResponseTransfer;
}
