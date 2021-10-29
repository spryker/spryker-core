<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationWishlist\Business;

use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer;
use Generated\Shared\Transfer\WishlistPreUpdateItemCheckResponseTransfer;
use Generated\Shared\Transfer\WishlistTransfer;

interface ProductConfigurationWishlistFacadeInterface
{
    /**
     * Specification:
     * - Requires `WishlistItem.sku` to be set.
     * - Checks if product configuration exists by provided `WishlistItem.sku` transfer property.
     * - Returns `WishlistPreAddItemCheckResponseTransfer.success=true` if product configuration is found, sets `WishlistPreAddItemCheckResponseTransfer.success=false` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer
     */
    public function checkWishlistItemProductConfiguration(WishlistItemTransfer $wishlistItemTransfer): WishlistPreAddItemCheckResponseTransfer;

    /**
     * Specification:
     * - Requires `WishlistItem.sku` to be set.
     * - Checks if product configuration exists by provided `WishlistItem.sku` transfer property.
     * - Returns `WishlistPreUpdateItemCheckResponseTransfer.success=true` if product configuration is found, sets `WishlistPreUpdateItemCheckResponseTransfer.success=false` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistPreUpdateItemCheckResponseTransfer
     */
    public function checkUpdateWishlistItemProductConfiguration(WishlistItemTransfer $wishlistItemTransfer): WishlistPreUpdateItemCheckResponseTransfer;

    /**
     * Specification:
     * - Prepares product configuration attached to a wishlist item to be saved.
     * - Does nothing if product configuration instance is not set at wishlist item.
     * - Sets JSON encoded product configuration instance to wishlist item otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function expandWishlistItemWithProductConfigurationData(WishlistItemTransfer $wishlistItemTransfer): WishlistItemTransfer;

    /**
     * Specification:
     * - Expands `WishlistItem` transfer object with product configuration data.
     * - Returns expanded `WishlistItem` transfer object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function expandWishlistItemWithProductConfiguration(WishlistItemTransfer $wishlistItemTransfer): WishlistItemTransfer;

    /**
     * Specification:
     * - Expands `WishlistItem` transfers collection in `Wishlist` transfer object with product configuration data.
     * - Returns expanded `Wishlist` transfer object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function expandWishlistItemCollectionWithProductConfiguration(WishlistTransfer $wishlistTransfer): WishlistTransfer;

    /**
     * Specification:
     * - Checks if `Wishlist` transfer object contains configurable product items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return bool
     */
    public function hasConfigurableProductItems(WishlistTransfer $wishlistTransfer): bool;
}
