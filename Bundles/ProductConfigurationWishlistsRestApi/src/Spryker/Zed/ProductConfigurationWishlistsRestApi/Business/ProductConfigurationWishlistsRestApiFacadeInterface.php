<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationWishlistsRestApi\Business;

use ArrayObject;
use Generated\Shared\Transfer\WishlistItemRequestTransfer;
use Generated\Shared\Transfer\WishlistItemResponseTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;

interface ProductConfigurationWishlistsRestApiFacadeInterface
{
    /**
     * Specification:
     * - Requires `WishlistItemRequestTransfer::sku` to be provided.
     * - Finds an item by product sku + product configuration instance hash in collection of `WishlistItem` transfer objects.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\WishlistItemTransfer> $wishlistItemTransfers
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer|null
     */
    public function findWishlistItemByProductConfiguration(
        WishlistItemRequestTransfer $wishlistItemRequestTransfer,
        ArrayObject $wishlistItemTransfers
    ): ?WishlistItemTransfer;

    /**
     * Specification:
     * - Requires `WishlistItemRequestTransfer::sku` to be provided.
     * - Finds an item by product sku + product configuration instance hash in collection of `WishlistItem` transfer objects.
     * - Deletes found wishlist item.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\WishlistItemTransfer> $wishlistItemTransfers
     *
     * @return void
     */
    public function deleteWishlistItem(
        WishlistItemRequestTransfer $wishlistItemRequestTransfer,
        ArrayObject $wishlistItemTransfers
    ): void;

    /**
     * Specification:
     * - Requires `WishlistItemRequestTransfer.productConfigurationInstance` to be set.
     * - Expects `WishlistItemRequestTransfer.sku` to be provided.
     * - Finds an item by product sku + product configuration instance hash in collection of `WishlistItem` transfer objects.
     * - If sku in `WishlistItemRequestTransfer` is provided, sets sku to found wishlist item.
     * - Otherwise, uses found wishlist item's sku.
     * - Updates found wishlist item.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\WishlistItemTransfer> $wishlistItemTransfers
     *
     * @return \Generated\Shared\Transfer\WishlistItemResponseTransfer
     */
    public function updateWishlistItem(
        WishlistItemRequestTransfer $wishlistItemRequestTransfer,
        ArrayObject $wishlistItemTransfers
    ): WishlistItemResponseTransfer;
}
