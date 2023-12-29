<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\Filter;

use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListCriteriaTransfer;

interface ShoppingListFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShoppingListCriteriaTransfer $shoppingListCriteriaTransfer
     * @param \Generated\Shared\Transfer\ShoppingListCollectionTransfer $shoppingListCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    public function filterBlacklistedShoppingListsFromShoppingListCollection(
        ShoppingListCriteriaTransfer $shoppingListCriteriaTransfer,
        ShoppingListCollectionTransfer $shoppingListCollectionTransfer
    ): ShoppingListCollectionTransfer;
}
