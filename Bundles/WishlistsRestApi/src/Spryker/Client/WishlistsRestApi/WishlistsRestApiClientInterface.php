<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\WishlistsRestApi;

use Generated\Shared\Transfer\WishlistFilterTransfer;
use Generated\Shared\Transfer\WishlistItemRequestTransfer;
use Generated\Shared\Transfer\WishlistItemResponseTransfer;
use Generated\Shared\Transfer\WishlistRequestTransfer;
use Generated\Shared\Transfer\WishlistResponseTransfer;

interface WishlistsRestApiClientInterface
{
    /**
     * Specification:
     * - Makes Zed request.
     * - Updates existing wishlist records in DB.
     * - Required properties: WishlistRequestTransfer.uuid, WishlistRequestTransfer.idCustomer and WishlistRequestTransfer.wishlist.
     * - Returns WishlistResponseTransfer.isSuccessful = true and updated WishlistTransfer on success.
     * - Returns WishlistResponseTransfer.isSuccessful = false if the wishlist was not found by uuid or update was not successful.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistRequestTransfer $wishlistRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistResponseTransfer
     */
    public function updateWishlist(WishlistRequestTransfer $wishlistRequestTransfer): WishlistResponseTransfer;

    /**
     * Specification:
     * - Makes Zed request.
     * - Requires uuid, idCustomer to be set on the WishlistRequestTransfer.
     * - Deletes existing wishlist from DB.
     * - Returns WishlistResponseTransfer.isSuccessful = true on successful delete.
     * - Returns WishlistResponseTransfer.isSuccessful = false if the wishlist was not found by uuid.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistFilterTransfer $wishlistFilterTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistResponseTransfer
     */
    public function deleteWishlist(WishlistFilterTransfer $wishlistFilterTransfer): WishlistResponseTransfer;

    /**
     * Specification:
     * - Makes Zed request.
     * - Requires idCustomer, uuidWishlist, sku to be set on WishlistItemRequestTransfer.
     * - Looks up the wishlist by uuid.
     * - Adds product to the wishlist found in the previous step.
     * - Returns WishlistItemResponseTransfer.isSuccessful = true and added WishlistItemTransfer on success.
     * - Returns WishlistItemResponseTransfer.isSuccessful = false if the wishlist was not found by uuid or adding the item was not successful.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemResponseTransfer
     */
    public function addWishlistItem(WishlistItemRequestTransfer $wishlistItemRequestTransfer): WishlistItemResponseTransfer;

    /**
     * Specification:
     * - Makes Zed request.
     * - Requires idCustomer, uuidWishlist, sku to be set on WishlistItemRequestTransfer.
     * - Looks up the wishlist by uuid.
     * - Removes product from the wishlist found in the previous step.
     * - Returns WishlistItemResponseTransfer.isSuccessful = true on successful delete.
     * - Returns WishlistItemResponseTransfer.isSuccessful = false if the wishlist was not found by uuid or the item is not found in the wishlist.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemResponseTransfer
     */
    public function deleteWishlistItem(WishlistItemRequestTransfer $wishlistItemRequestTransfer): WishlistItemResponseTransfer;
}
