<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WishlistsRestApi\Business;

use Generated\Shared\Transfer\WishlistRequestTransfer;
use Generated\Shared\Transfer\WishlistResponseTransfer;

interface WishlistsRestApiFacadeInterface
{
    /**
     * Specification:
     *  - Updates existing wishlist records in DB with generated UUID value.
     *
     * @api
     *
     * @deprecated Will be removed in the next major.
     *
     * @return void
     */
    public function updateWishlistsUuid(): void;

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
     *  - Required properties: uuid, fkCustomer.
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
