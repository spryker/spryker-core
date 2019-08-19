<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\WishlistsRestApi;

use Generated\Shared\Transfer\WishlistRequestTransfer;
use Generated\Shared\Transfer\WishlistResponseTransfer;

interface WishlistsRestApiClientInterface
{
    /**
     * Specification:
     *  - Finds one wishlist by uuid and customer id.
     *  - Returns wishlist response with wishlist.
     *  - If error occurred returns wishlist response with errors.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistRequestTransfer $wishlistRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistResponseTransfer
     */
    public function getWishlistByIdCustomerAndUuid(WishlistRequestTransfer $wishlistRequestTransfer): WishlistResponseTransfer;

    /**
     * Specification:
     *  - Updates existing wishlist records in DB.
     *  - Required properties: uuid, idCustomer and wishlist.
     *  - Returns wishlist response with updated wishlist.
     *  - If error occurred returns wishlist response with errors.
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
     *  - Deletes existing wishlist from DB.
     *  - If error occurred returns wishlist response with errors.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistRequestTransfer $wishlistRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistResponseTransfer
     */
    public function deleteWishlist(WishlistRequestTransfer $wishlistRequestTransfer): WishlistResponseTransfer;
}
