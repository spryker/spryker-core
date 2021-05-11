<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WishlistExtension\Dependency\Plugin;

use Generated\Shared\Transfer\WishlistItemTransfer;

/**
 * Executed before adding a `WishlistItem` transfer object to a `Wishlist`.
 */
interface WishlistPreAddItemPluginInterface
{
    /**
     * Specification:
     * - Executed before adding a `WishlistItem` transfer object to a `Wishlist`.
     * - Expands `WishlistItem` transfer object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function preAddItem(WishlistItemTransfer $wishlistItemTransfer): WishlistItemTransfer;
}
