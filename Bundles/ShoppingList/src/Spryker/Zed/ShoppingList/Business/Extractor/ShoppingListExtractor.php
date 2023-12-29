<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\Extractor;

use Generated\Shared\Transfer\ShoppingListCollectionTransfer;

class ShoppingListExtractor implements ShoppingListExtractorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShoppingListCollectionTransfer $shoppingListCollectionTransfer
     *
     * @return list<int>
     */
    public function extractShoppingListIdsFromShoppingListCollection(ShoppingListCollectionTransfer $shoppingListCollectionTransfer): array
    {
        $shoppingListIds = [];

        /** @var \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer */
        foreach ($shoppingListCollectionTransfer->getShoppingLists() as $shoppingListTransfer) {
            $shoppingListIds[] = $shoppingListTransfer->getIdShoppingListOrFail();
        }

        return $shoppingListIds;
    }
}
