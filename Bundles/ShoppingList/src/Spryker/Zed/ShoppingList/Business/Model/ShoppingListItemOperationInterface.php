<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\Model;

use Generated\Shared\Transfer\ShoppingListItemResponseTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;

interface ShoppingListItemOperationInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function addItem(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return void
     */
    public function deleteShoppingListItems(ShoppingListTransfer $shoppingListTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function deleteShoppingListItem(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function removeItemById(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function saveShoppingListItem(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function saveShoppingListItemWithoutPermissionsCheck(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer;
}
