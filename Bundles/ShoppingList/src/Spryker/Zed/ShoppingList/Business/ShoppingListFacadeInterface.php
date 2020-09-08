<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListDismissRequestTransfer;
use Generated\Shared\Transfer\ShoppingListFromCartRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemResponseTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListOverviewRequestTransfer;
use Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer;
use Generated\Shared\Transfer\ShoppingListPermissionGroupCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListShareRequestTransfer;
use Generated\Shared\Transfer\ShoppingListShareResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;

interface ShoppingListFacadeInterface
{
    /**
     * Specification:
     * - Create new shopping list entity if it does not exist.
     * - Executes ShoppingListItemBulkPostSavePlugin plugins after create.
     * - Add create shopping list success message.
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
     * - Update shopping list entity if it exist or create new.
     * - Executes ShoppingListItemBulkPostSavePlugin plugins after save.
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
     *  - Remove all items related to shipping list.
     *  - Remove all shared with company user relations of shopping list.
     *  - Remove all shared with company business unit relations of shopping list.
     *  - Remove shopping list.
     *  - Executes ShoppingListEvents::SHOPPING_LIST_UNPUBLISH event after removing.
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
     *  - Remove all shopping list items.
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
     * - Adds item to shopping list.
     * - Requires ShoppingListItemTransfer.sku, ShoppingListItemTransfer.quantity.
     * - Creates shopping list for customer if ShoppingListItemTransfer.fkShoppingList is not provided.
     * - Checks shopping list write permissions.
     * - Executes ShoppingListItemPostSavePlugin plugins after save.
     * - Adds create shopping list success message if shopping list created.
     * - Fails and adds error message when the product does not exist.
     * - Fails and adds error message when quantity is less or equal to zero or greater than 2147483647.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function addShoppingListItem(
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ShoppingListItemResponseTransfer;

    /**
     * Specification:
     * - Updates shopping list item.
     * - Requires ShoppingListItemTransfer.idShoppingListItem, ShoppingListItemTransfer.fkShoppingList, ShoppingListItemTransfer.quantity.
     * - Checks shopping list write permissions.
     * - Executes ShoppingListItemPostSavePlugin plugins after save.
     * - Fails when quantity is less or equal to zero or greater than 2147483647.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function updateShoppingListItemById(
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ShoppingListItemResponseTransfer;

    /**
     * Specification:
     *  - Adds item to shopping list.
     *  - Adds create shopping list success message if shopping list created.
     *  - Fails and adds error message when quantity is lesser equal than zero.
     *
     * @api
     *
     * @deprecated Use {@link addShoppingListItem()} instead. Will be removed with next major release.
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function addItem(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer;

    /**
     * Specification:
     * - Adds items to the shopping list in persistence.
     * - Adds success and failed messages through messenger facade.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function addItems(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer;

    /**
     * Specification:
     * - Removes shopping list item by id from the database, using transaction.
     * - Returns `ShoppingListItemResponseTransfer` with 'isSuccess=false' if item does not exist.
     * - Loads shopping list with items by shopping list id from the database.
     * - Executes `ShoppingListItemExpanderPluginInterface` plugin stack before deletion.
     * - Executes `ShoppingListItemCollectionExpanderPluginInterface` plugin stack before deletion.
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
     * - Loads shopping list by id.
     * - Expands shopping list items with currency ISO code and price mode data.
     * - Executes `ShoppingListItemExpanderPluginInterface` plugin stack.
     * - Executes `ShoppingListItemCollectionExpanderPluginInterface` plugin stack.
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
     * - Gets shopping list detail information.
     * - Expands shopping list items with currency iso code and price mode data.
     * - Executes `ShoppingListItemExpanderPluginInterface` plugin stack.
     * - Executes `ShoppingListItemCollectionExpanderPluginInterface` plugin stack.
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
     *  - Get shopping list collection by customer.
     *  - Shopping lists blacklisted for customer will be hidden.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    public function getCustomerShoppingListCollection(CustomerTransfer $customerTransfer): ShoppingListCollectionTransfer;

    /**
     * Specification:
     *  - Get items collection for shopping list collection.
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
     *  - Get shopping list item collection by ids.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function getShoppingListItemCollectionTransfer(
        ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
    ): ShoppingListItemCollectionTransfer;

    /**
     * Specification:
     *  - Update shopping list item.
     *
     * @api
     *
     * @deprecated Use {@link updateShoppingListItemById()} instead. Will be removed with next major release.
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function updateShoppingListItem(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer;

    /**
     * Specification:
     *  - Finds shopping list by id if exists.
     *  - Finds or creates shopping list by name if shopping list id absent.
     *  - Push items from quote to shopping list.
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
     *  - Install shopping list permissions.
     *
     * @api
     *
     * @return void
     */
    public function installShoppingListPermissions(): void;

    /**
     * Specification:
     *  - Get shopping list permission groups.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ShoppingListPermissionGroupCollectionTransfer
     */
    public function getShoppingListPermissionGroups(): ShoppingListPermissionGroupCollectionTransfer;

    /**
     * Specification:
     *  - Share shopping list with company business unit.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListShareRequestTransfer $shoppingListShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListShareResponseTransfer
     */
    public function shareShoppingListWithCompanyBusinessUnit(
        ShoppingListShareRequestTransfer $shoppingListShareRequestTransfer
    ): ShoppingListShareResponseTransfer;

    /**
     * Specification:
     *  - Share shopping list with company user.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListShareRequestTransfer $shoppingListShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListShareResponseTransfer
     */
    public function shareShoppingListWithCompanyUser(ShoppingListShareRequestTransfer $shoppingListShareRequestTransfer): ShoppingListShareResponseTransfer;

    /**
     * Specification:
     *  - Updates share shopping list with company business units and company users.
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
     *  - Finds company user shopping list permissions.
     *
     * @api
     *
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findCompanyUserPermissions(int $idCompanyUser): PermissionCollectionTransfer;

    /**
     * Specification:
     *  - Remove company business unit from shared shopping list.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListShareRequestTransfer $shoppingListShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListShareResponseTransfer
     */
    public function unShareShoppingListWithCompanyBusinessUnit(
        ShoppingListShareRequestTransfer $shoppingListShareRequestTransfer
    ): ShoppingListShareResponseTransfer;

    /**
     * Specification:
     *  - Remove company user relation from shared shopping lists.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListShareRequestTransfer $shoppingListShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListShareResponseTransfer
     */
    public function unShareCompanyUserShoppingLists(ShoppingListShareRequestTransfer $shoppingListShareRequestTransfer): ShoppingListShareResponseTransfer;

    /**
     * Specification:
     *  - Removes shopping list to company user relation if exists.
     *  - Adds shopping list to company user blacklist if company user business unit has access to shopping list.
     *  - Returns success if at least one action was executed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListDismissRequestTransfer $shoppingListDismissRequest
     *
     * @return \Generated\Shared\Transfer\ShoppingListShareResponseTransfer
     */
    public function dismissShoppingListSharing(ShoppingListDismissRequestTransfer $shoppingListDismissRequest): ShoppingListShareResponseTransfer;

    /**
     * Specification:
     * - Finds shopping list by uuid.
     * - Requires uuid field to be set in ShoppingListTransfer.
     * - Requires idCompanyUser field to be set in ShoppingListTransfer.
     *
     * @api
     *
     * {@internal will work if uuid field is provided.}
     *
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function findShoppingListByUuid(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer;

    /**
     * Specification:
     *  - Checks if product in shopping list item is active.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer
     */
    public function checkShoppingListItemProductIsActive(
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ShoppingListPreAddItemCheckResponseTransfer;
}
