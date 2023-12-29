<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\Extractor;

use Generated\Shared\Transfer\ShoppingListCollectionTransfer;

interface ShoppingListExtractorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShoppingListCollectionTransfer $shoppingListCollectionTransfer
     *
     * @return list<int>
     */
    public function extractShoppingListIdsFromShoppingListCollection(ShoppingListCollectionTransfer $shoppingListCollectionTransfer): array;
}
