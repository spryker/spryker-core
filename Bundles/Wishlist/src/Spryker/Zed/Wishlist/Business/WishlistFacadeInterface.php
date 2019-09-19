<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Business;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\WishlistItemCollectionTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistOverviewRequestTransfer;
use Generated\Shared\Transfer\WishlistRequestTransfer;
use Generated\Shared\Transfer\WishlistResponseTransfer;
use Generated\Shared\Transfer\WishlistTransfer;

interface WishlistFacadeInterface
{
    /**
     * Specification:
     *  - Creates wishlist for a specific customer with given name
     *  - Required values of WishlistTransfer: name, fkCustomer.
     *  - Returns WishlistTransfer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function createWishlist(WishlistTransfer $wishlistTransfer);

    /**
     * Specification:
     *  - Validates if the wishlist has unique name for the customer.
     *  - Creates wishlist for a specific customer with given name.
     *  - Required values of WishlistTransfer: name, fkCustomer.
     *  - Returns WishlistResponseTransfer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistResponseTransfer
     */
    public function validateAndCreateWishlist(WishlistTransfer $wishlistTransfer);

    /**
     * Specification:
     *  - Updates wishlist
     *  - Required values of WishlistTransfer: idWishlist.
     *  - Returns WishlistTransfer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function updateWishlist(WishlistTransfer $wishlistTransfer);

    /**
     * Specification:
     *  - Validates if the modified wishlist name is unique for the customer.
     *  - Updates wishlist.
     *  - Required values of WishlistTransfer: idWishlist.
     *  - Returns WishlistResponseTransfer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistResponseTransfer
     */
    public function validateAndUpdateWishlist(WishlistTransfer $wishlistTransfer);

    /**
     * Specification:
     *  - Removes wishlist and its items
     *  - Required values of WishlistTransfer: idWishlist
     *  - Returns WishlistTransfer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function removeWishlist(WishlistTransfer $wishlistTransfer);

    /**
     * Specification:
     *  - Removes wishlist and its items
     *  - Required values of WishlistTransfer: fkCustomer, name
     *  - Returns WishlistTransfer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function removeWishlistByName(WishlistTransfer $wishlistTransfer);

    /**
     * Specification:
     *  - Adds collection of items to a wishlist
     *  - Required values of WishlistTransfer: fkCustomer, name
     *  - Required values of WishlistItemTransfer: fkProduct
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     * @param \Generated\Shared\Transfer\WishlistItemTransfer[] $wishlistItemCollection
     *
     * @return void
     */
    public function addItemCollection(WishlistTransfer $wishlistTransfer, array $wishlistItemCollection);

    /**
     * Specification:
     *  - Removes all wishlist items
     *  - Required values: idWishlist
     *  - Returns WishlistTransfer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return void
     */
    public function emptyWishlist(WishlistTransfer $wishlistTransfer);

    /**
     * Specification:
     *  - Adds item to wishlist
     *  - Required values of WishlistItemTransfer: fkCustomer, fkProduct. Optional: wishlistName
     *    In case wishlist name is not provided the default value will be used
     *  - Returns WishlistItemTransfer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function addItem(WishlistItemTransfer $wishlistItemTransfer);

    /**
     * Specification:
     *  - Removes item from wishlist
     *  - Required values of WishlistItemTransfer: fkCustomer, sku. Optional: wishlistName
     *    In case wishlist name is not provided the default value will be used
     *  - Returns WishlistItemTransfer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function removeItem(WishlistItemTransfer $wishlistItemTransfer);

    /**
     * Specification:
     *  - Removes item from wishlist
     *  - Required values of WishlistItemTransfer: fkCustomer, sku. Optional: wishlistName
     *    In case wishlist name is not provided the default value will be used
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemCollectionTransfer $wishlistItemTransferCollection
     *
     * @return \Generated\Shared\Transfer\WishlistItemCollectionTransfer
     */
    public function removeItemCollection(WishlistItemCollectionTransfer $wishlistItemTransferCollection);

    /**
     * Specification:
     *  - Returns wishlist by specific name for a given customer
     *  - Required values: fkCustomer, name
     *  - Returns WishlistItemTransfer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function getWishlistByName(WishlistTransfer $wishlistTransfer);

    /**
     * Specification:
     *  - Returns wishlist by specific name for a given customer, with paginated items.
     *  - Pagination is controlled with page, itemsPerPage, orderBy and orderDirection values of WishlistOverviewRequestTransfer.
     *  - Required values of WishlistTransfer: fkCustomer, name.
     *  - Required values of WishlistOverviewRequestTransfer: WishlistTransfer.
     *  - Returns WishlistOverviewResponseTransfer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistOverviewRequestTransfer $wishlistOverviewRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistOverviewResponseTransfer
     */
    public function getWishlistOverview(WishlistOverviewRequestTransfer $wishlistOverviewRequestTransfer);

    /**
     * Specification:
     *  - Returns all wishlist entities for the given customer.
     *  - Required values of CustomerTransfer: idCustomer.
     *  - Returns WishlistCollectionTransfer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistCollectionTransfer
     */
    public function getCustomerWishlistCollection(CustomerTransfer $customerTransfer);

    /**
     * Specification:
     * - Returns wishlist for a given customer by uuid.
     * - Required values of WishlistRequestTransfer::$wishlist: fkCustomer, uuid.
     *
     * @api
     *
     * {@internal will work if uuid field is provided.}
     *
     * @param \Generated\Shared\Transfer\WishlistRequestTransfer $wishlistRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistResponseTransfer
     */
    public function getCustomerWishlistByUuid(WishlistRequestTransfer $wishlistRequestTransfer): WishlistResponseTransfer;
}
