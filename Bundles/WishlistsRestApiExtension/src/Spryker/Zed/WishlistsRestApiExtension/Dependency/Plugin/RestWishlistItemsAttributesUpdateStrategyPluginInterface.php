<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WishlistsRestApiExtension\Dependency\Plugin;

use ArrayObject;
use Generated\Shared\Transfer\WishlistItemRequestTransfer;
use Generated\Shared\Transfer\WishlistItemResponseTransfer;

/**
 * Provides ability to update wishlist items.
 */
interface RestWishlistItemsAttributesUpdateStrategyPluginInterface
{
    /**
     * Specification:
     * - Checks if plugin is applicable for a given `WishlistItemRequest` transfer object and collection of `WishlistItem` transfer objects.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\WishlistItemTransfer> $wishlistItemTransfers
     *
     * @return bool
     */
    public function isApplicable(WishlistItemRequestTransfer $wishlistItemRequestTransfer, ArrayObject $wishlistItemTransfers): bool;

    /**
     * Specification:
     * - Updates Wishlist item by given `WishlistItemRequest` transfer object and collection of `WishlistItem` transfer objects.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\WishlistItemTransfer> $wishlistItemTransfers
     *
     * @return \Generated\Shared\Transfer\WishlistItemResponseTransfer
     */
    public function update(
        WishlistItemRequestTransfer $wishlistItemRequestTransfer,
        ArrayObject $wishlistItemTransfers
    ): WishlistItemResponseTransfer;
}
