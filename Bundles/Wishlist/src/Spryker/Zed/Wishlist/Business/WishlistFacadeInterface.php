<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Business;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\WishlistFilterTransfer;
use Generated\Shared\Transfer\WishlistItemCollectionTransfer;
use Generated\Shared\Transfer\WishlistItemCriteriaTransfer;
use Generated\Shared\Transfer\WishlistItemResponseTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistOverviewRequestTransfer;
use Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer;
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
     * @deprecated Use {@link \Spryker\Zed\Wishlist\Business\WishlistFacadeInterface::validateAndCreateWishlist()} instead.
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
     * @deprecated Use {@link \Spryker\Zed\Wishlist\Business\WishlistFacadeInterface::validateAndUpdateWishlist()} instead.
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
     * - Adds collection of items to a wishlist.
     * - Requires `WishlistTransfer.fkCustomer` and `WishlistTransfer.name` to be set.
     * - Requires `WishlistItemTransfer.fkCustomer`, `WishlistItemTransfer.sku ` and `WishlistItemTransfer.wishlistName`
     *   for each item in collection to be set.
     * - In case `WishlistItemTransfer.wishlistName === ''` the default value will be used.
     * - Validates wishlist name for each item in collection.
     * - If wishlist name validation for item failed, the corresponding product is inactive or does not exist - item will not be added.
     * - Executes `AddItemPreCheckPluginInterface` plugin stack for each item in collection.
     * - If one of `AddItemPreCheckPluginInterface` stack plugins will return `WishlistPreAddItemCheckResponseTransfer.success=false`- item will not be added.
     * - Executes `WishlistPreAddItemPluginInterface`plugin stack.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     * @param array<\Generated\Shared\Transfer\WishlistItemTransfer> $wishlistItemCollection
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
     * - Adds item to wishlist.
     * - Requires `WishlistItemTransfer.fkCustomer`, `WishlistItemTransfer.sku` and `WishlistItemTransfer.wishlistName` to be set.
     * - In case `WishlistItemTransfer.wishlistName === ''` the default value will be used.
     * - Validates wishlist name.
     * - If wishlist name validation for item failed, the corresponding product is inactive or does not exist - item will not be added
     *   and the same `WishlistItemTransfer` will be returned.
     * - Executes `AddItemPreCheckPluginInterface` plugin stack.
     * - If one of `AddItemPreCheckPluginInterface` stack plugins will return `WishlistPreAddItemCheckResponseTransfer.success=false`-
     *   item will not be added.
     * - Executes `WishlistPreAddItemPluginInterface` plugin stack.
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
     *  - Removes item by provided `WishlistItem` transfer object from wishlist.
     *  - Returns `WishlistItem` transfer object.
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
     *  - Removes items from wishlist.
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
     * @deprecated Use {@link getWishlistByFilter()} instead.
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
     * - Returns wishlist for a given WishlistFilterTransfer.
     * - Required value is WishlistFilterTransfer.idCustomer.
     * - Returns WishlistResponseTransfer.isSuccess true on success and false otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistFilterTransfer $wishlistFilterTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistResponseTransfer
     */
    public function getWishlistByFilter(WishlistFilterTransfer $wishlistFilterTransfer): WishlistResponseTransfer;

    /**
     * Specification:
     * - Retrieves wishlist item by data provided in the `WishlistItemCriteriaTransfer`.
     * - Executes `WishlistItemExpanderPluginInterface` plugins stack.
     * - Returns "isSuccess=true" and return transfer on success or `isSuccess=false` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemCriteriaTransfer $wishlistItemCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemResponseTransfer
     */
    public function getWishlistItem(WishlistItemCriteriaTransfer $wishlistItemCriteriaTransfer): WishlistItemResponseTransfer;

    /**
     * Specification:
     * - Expects `WishlistItemTransfer.idWishlistItem` to be set.
     * - Expects `WishlistItemTransfer.sku` to be set.
     * - Executes `UpdateItemPreCheckPluginInterface` plugin stack.
     * - Retrieves wishlist item by data provided in the `WishlistItemTransfer` from Persistence.
     * - Executes `WishlistPreUpdateItemPluginInterface` plugin stack.
     * - Updates existing wishlist item in database.
     * - Returns `isSuccess=true` with updated wishlist item on success or `isSuccess=false` with error messages otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemResponseTransfer
     */
    public function updateWishlistItem(WishlistItemTransfer $wishlistItemTransfer): WishlistItemResponseTransfer;

    /**
     * Specification:
     * - Requires `WishlistItemTransfer.wishlistName` to be set.
     * - Requires `WishlistItemTransfer.fkCustomer` to be set.
     * - Validates wishlist item's wishlist before creation.
     * - Checks wishlist existence by `WishlistItemTransfer.wishlistName` and `WishlistItemTransfer.fkCustomer`.
     * - Returns `WishlistPreAddItemCheckResponseTransfer.isSuccess = true` if wishlist exists, is default or
     *   `WishlistItemTransfer.wishlistName` is an empty string.
     * - Returns `WishlistPreAddItemCheckResponseTransfer.isSuccess = false` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer
     */
    public function validateWishlistItemBeforeCreation(
        WishlistItemTransfer $wishlistItemTransfer
    ): WishlistPreAddItemCheckResponseTransfer;
}
