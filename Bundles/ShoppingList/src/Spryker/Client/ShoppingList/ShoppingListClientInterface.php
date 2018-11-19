<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingList;

use Generated\Shared\Transfer\ShoppingListAddToCartRequestCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListDismissRequestTransfer;
use Generated\Shared\Transfer\ShoppingListFromCartRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemResponseTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListOverviewRequestTransfer;
use Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer;
use Generated\Shared\Transfer\ShoppingListPermissionGroupCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListShareRequestTransfer;
use Generated\Shared\Transfer\ShoppingListShareResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;

interface ShoppingListClientInterface
{
    /**
     * Specification:
     *  - Makes Zed request.
     *  - Create new shopping list entity if it does not exist.
     *  - Updates customer permissions.
     *  - Get messages from zed request and put them to session.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function createShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer;

    /**
     * Specification:
     *  - Makes Zed request.
     *  - Update shopping list entity if it exist or create new.
     *  - Updates customer permissions.
     *  - Get messages from zed request and put them to session.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function updateShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer;

    /**
     * Specification:
     *  - Makes Zed request.
     *  - Remove shopping list.
     *  - Updates customer permissions.
     *  - Get messages from zed request and put them to session.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function removeShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer;

    /**
     * Specification:
     *  - Makes Zed request.
     *  - Remove all shopping list items.
     *  - Get messages from Zed request and put them to session.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function clearShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer;

    /**
     * Specification:
     *  - Makes Zed request.
     *  - Hydrates ShoppingListItem with provided optional params.
     *  - Add item to shopping list.
     *  - Updates customer permissions.
     *  - Get messages from zed request and put them to session.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function addItem(ShoppingListItemTransfer $shoppingListItemTransfer, array $params = []): ShoppingListItemTransfer;

    /**
     * Specification:
     *  - Makes Zed request.
     *  - Remove item by id.
     *  - Updates customer permissions.
     *  - Get messages from zed request and put them to session.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function removeItemById(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemResponseTransfer;

    /**
     * Specification:
     *  - Makes Zed request.
     *  - Load shopping list by id.
     *  - Updates customer permissions.
     *  - Get messages from zed request and put them to session.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function getShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListTransfer;

    /**
     * Specification:
     *  - Makes Zed request.
     *  - Create new shopping list entity if it does not exist.
     *  - Updates customer permissions.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListOverviewRequestTransfer $shoppingListOverviewRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer
     */
    public function getShoppingListOverviewWithoutProductDetails(ShoppingListOverviewRequestTransfer $shoppingListOverviewRequestTransfer): ShoppingListOverviewResponseTransfer;

    /**
     * Specification:
     *  - Makes Zed request.
     *  - Get shopping list detail information.
     *  - Updates customer permissions.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListOverviewRequestTransfer $shoppingListOverviewRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer
     */
    public function getShoppingListOverview(ShoppingListOverviewRequestTransfer $shoppingListOverviewRequestTransfer): ShoppingListOverviewResponseTransfer;

    /**
     * Specification:
     *  - Makes Zed request.
     *  - Get shopping list collection by customer.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    public function getCustomerShoppingListCollection(): ShoppingListCollectionTransfer;

    /**
     * Specification:
     *  - Makes Zed request.
     *  - Add shopping list items to cart.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListAddToCartRequestCollectionTransfer $shoppingListAddToCartRequestCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListAddToCartRequestCollectionTransfer
     */
    public function addItemCollectionToCart(ShoppingListAddToCartRequestCollectionTransfer $shoppingListAddToCartRequestCollectionTransfer): ShoppingListAddToCartRequestCollectionTransfer;

    /**
     * Specification:
     *  - Makes Zed request.
     *  - Get items collection for shopping list collection.
     *  - Updates customer permissions.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListCollectionTransfer $shoppingListCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function getShoppingListItemCollection(ShoppingListCollectionTransfer $shoppingListCollectionTransfer): ShoppingListItemCollectionTransfer;

    /**
     * Specification:
     *  - Makes Zed request.
     *  - Get shopping list item collection by ids.
     *  - Updates customer permissions.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function getShoppingListItemCollectionTransfer(ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer): ShoppingListItemCollectionTransfer;

    /**
     * Specification:
     *  - Makes Zed request.
     *  - Update shopping list item.
     *  - Updates customer permissions.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function updateShoppingListItem(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer;

    /**
     * Specification:
     *  - Makes Zed request.
     *  - Push items from quote to shopping list.
     *  - Updates customer permissions.
     *  - Get messages from zed request and put them to session.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListFromCartRequestTransfer $shoppingListFromCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function createShoppingListFromQuote(ShoppingListFromCartRequestTransfer $shoppingListFromCartRequestTransfer): ShoppingListTransfer;

    /**
     * Specification:
     *  - Makes Zed request.
     *  - Get shopping list permission groups.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ShoppingListPermissionGroupCollectionTransfer
     */
    public function getShoppingListPermissionGroups(): ShoppingListPermissionGroupCollectionTransfer;

    /**
     * Specification:
     *  - Makes Zed request.
     *  - Share shopping list with company users from business unit or exact company user.
     *  - Updates customer permissions.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListShareRequestTransfer $shoppingListShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListShareResponseTransfer
     */
    public function shareShoppingList(ShoppingListShareRequestTransfer $shoppingListShareRequestTransfer): ShoppingListShareResponseTransfer;

    /**
     * Specification:
     *  - Makes Zed request.
     *  - Updates share shopping list with company users from business unit or exact company user.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListShareResponseTransfer
     */
    public function updateShoppingListSharedEntities(ShoppingListTransfer $shoppingListTransfer): ShoppingListShareResponseTransfer;

    /**
     * Specification:
     *  - Makes zed request.
     *  - Removes shopping list to company user relation if exists.
     *  - Adds shopping list to company user blacklist if company user business unit has access to shopping list.
     *  - Returns success if at least one action was executed.
     *  - Updates customer permissions.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListDismissRequestTransfer $shoppingListDismissRequest
     *
     * @return \Generated\Shared\Transfer\ShoppingListShareResponseTransfer
     */
    public function dismissShoppingListSharing(ShoppingListDismissRequestTransfer $shoppingListDismissRequest): ShoppingListShareResponseTransfer;
}
