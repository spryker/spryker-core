<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\ShoppingListItem;

use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer;

interface ShoppingListItemCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer
     */
    public function checkShoppingListItemProductHasValidStore(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListPreAddItemCheckResponseTransfer;
}
