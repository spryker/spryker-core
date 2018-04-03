<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Persistence;

use Generated\Shared\Transfer\SpyShoppingListEntityTransfer;
use Generated\Shared\Transfer\SpyShoppingListItemEntityTransfer;

interface ShoppingListEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListEntityTransfer $shoppingListEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyShoppingListEntityTransfer
     */
    public function saveShoppingList(SpyShoppingListEntityTransfer $shoppingListEntityTransfer): SpyShoppingListEntityTransfer;

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListEntityTransfer $shoppingListEntityTransfer
     *
     * @return void
     */
    public function deleteShoppingListByName(SpyShoppingListEntityTransfer $shoppingListEntityTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListEntityTransfer $shoppingListEntityTransfer
     *
     * @return void
     */
    public function deleteShoppingListItems(SpyShoppingListEntityTransfer $shoppingListEntityTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListItemEntityTransfer $shoppingListItemEntityTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\EntityTransferInterface|\Generated\Shared\Transfer\SpyShoppingListItemEntityTransfer
     */
    public function saveShoppingListItem(SpyShoppingListItemEntityTransfer $shoppingListItemEntityTransfer): SpyShoppingListItemEntityTransfer;

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListItemEntityTransfer $shoppingListItemEntityTransfer
     *
     * @return void
     */
    public function deleteShoppingListItem(SpyShoppingListItemEntityTransfer $shoppingListItemEntityTransfer): void;
}
