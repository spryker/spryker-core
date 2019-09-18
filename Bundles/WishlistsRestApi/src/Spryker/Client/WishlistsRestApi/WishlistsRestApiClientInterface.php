<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\WishlistsRestApi;

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
     * - Required properties: uuid, idCustomer and wishlist.
     * - Returns wishlist response with updated wishlist.
     * - If error occurs, returns wishlist response with errors.
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
     * - Deletes existing wishlist from DB.
     * - If error occurs, returns wishlist response with errors.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistRequestTransfer $wishlistRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistResponseTransfer
     */
    public function deleteWishlist(WishlistRequestTransfer $wishlistRequestTransfer): WishlistResponseTransfer;

    /**
     * Specification:
     * - Makes Zed request.
     * - Requires idCustomer, uuidWishlist, sku to be set on WishlistItemRequestTransfer.
     * - Looks up the wishlist by uuid.
     * - In case wishlist is not found, return error.
     * - Adds product to the wishlist found in the previous step.
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
     * - In case wishlist is not found, return error.
     * - Removes product from the wishlist found in the previous step.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemResponseTransfer
     */
    public function deleteWishlistItem(WishlistItemRequestTransfer $wishlistItemRequestTransfer): WishlistItemResponseTransfer;
}
