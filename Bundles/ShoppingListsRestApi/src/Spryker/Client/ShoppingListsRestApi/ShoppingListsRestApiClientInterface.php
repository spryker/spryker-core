<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListsRestApi;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestShoppingListCollectionResponseTransfer;
use Generated\Shared\Transfer\ShoppingListItemRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemResponseTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;

interface ShoppingListsRestApiClientInterface
{
    /**
     * Specification:
     *  - Retrieves company user by uuid.
     *  - Checks that company user belongs to current customer.
     *  - Gets shopping list collection by the customer reference.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\RestShoppingListCollectionResponseTransfer
     */
    public function getCustomerShoppingListCollection(CustomerTransfer $customerTransfer): RestShoppingListCollectionResponseTransfer;

    /**
     * Specification:
     *  - Retrieves company user by uuid.
     *  - Checks that company user belongs to current customer.
     *  - Creates new shopping list if shopping list with given name does not already exist.
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
     *  - Retrieves company user by uuid.
     *  - Checks that company user belongs to current customer.
     *  - Updates the shopping list's name if shopping list with given name does not already exist.
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
     *  - Retrieves company user by uuid.
     *  - Checks that company user belongs to current customer.
     *  - Checks that shopping list exists and belongs to the customer.
     *  - Deletes the shopping list.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function deleteShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer;

    /**
     * Specification:
     *  - Retrieves company user by uuid.
     *  - Checks that company user belongs to current customer.
     *  - Checks that shopping list exists and belongs to the customer.
     *  - Adds item to shopping list.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemRequestTransfer $shoppingListItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function addShoppingListItem(
        ShoppingListItemRequestTransfer $shoppingListItemRequestTransfer
    ): ShoppingListItemResponseTransfer;

    /**
     * Specification:
     *  - Retrieves company user by uuid.
     *  - Checks that company user belongs to current customer.
     *  - Retrieves shopping list by uuid.
     *  - Retrieves shopping list item by uuid.
     *  - Removes item from shopping.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemRequestTransfer $shoppingListItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function deleteShoppingListItem(
        ShoppingListItemRequestTransfer $shoppingListItemRequestTransfer
    ): ShoppingListItemResponseTransfer;

    /**
     *  Specification:
     *  - Retrieves company user by uuid.
     *  - Checks that company user belongs to current customer.
     *  - Retrieves shopping list by uuid.
     *  - Retrieves shopping list item by uuid.
     *  - Updates shopping list item.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemRequestTransfer $shoppingListItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function updateShoppingListItem(
        ShoppingListItemRequestTransfer $shoppingListItemRequestTransfer
    ): ShoppingListItemResponseTransfer;
}
