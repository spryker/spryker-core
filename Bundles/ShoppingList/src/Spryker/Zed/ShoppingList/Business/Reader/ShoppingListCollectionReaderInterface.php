<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\Reader;

use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListCriteriaTransfer;

interface ShoppingListCollectionReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShoppingListCriteriaTransfer $shoppingListCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    public function getShoppingListCollection(ShoppingListCriteriaTransfer $shoppingListCriteriaTransfer): ShoppingListCollectionTransfer;
}
