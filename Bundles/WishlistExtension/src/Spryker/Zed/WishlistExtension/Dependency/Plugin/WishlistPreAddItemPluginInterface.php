<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WishlistExtension\Dependency\Plugin;

use Generated\Shared\Transfer\WishlistItemTransfer;

/**
 * Executes before add item to wishlist.
 */
interface WishlistPreAddItemPluginInterface
{
    /**
     * Specification:
     * - Runs before add WishlistItem to wishlist.
     * - Expands WishlistItem.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function preAddItem(WishlistItemTransfer $wishlistItemTransfer): WishlistItemTransfer;
}
