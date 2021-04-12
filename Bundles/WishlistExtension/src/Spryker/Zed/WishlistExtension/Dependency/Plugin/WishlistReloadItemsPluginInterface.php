<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WishlistExtension\Dependency\Plugin;

use Generated\Shared\Transfer\WishlistTransfer;

/**
 * This plugin interface can be extended in order to populate the items of a wishlist with updated data.
 */
interface WishlistReloadItemsPluginInterface
{
    /**
     * Specification:
     * - Checks if the plugin is applicable for the given wishlist.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return bool
     */
    public function isApplicable(WishlistTransfer $wishlistTransfer): bool;

    /**
     * Specification:
     * - Populates wishlist items with updated data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function reloadItems(WishlistTransfer $wishlistTransfer): WishlistTransfer;
}
