<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationShoppingList\Replicator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;

class ProductConfigurationReplicator implements ProductConfigurationReplicatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function copyProductConfigurationFromShoppingListItemToQuoteItem(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ItemTransfer $itemTransfer
    ): ItemTransfer {
        $productConfigurationTransfer = $shoppingListItemTransfer->getProductConfigurationInstance();

        if ($productConfigurationTransfer) {
            $itemTransfer->setProductConfigurationInstance($productConfigurationTransfer);
        }

        return $itemTransfer;
    }
}
