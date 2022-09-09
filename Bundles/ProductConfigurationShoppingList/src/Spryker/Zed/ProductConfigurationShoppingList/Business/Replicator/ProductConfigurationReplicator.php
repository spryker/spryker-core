<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationShoppingList\Business\Replicator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;

class ProductConfigurationReplicator implements ProductConfigurationReplicatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function copyProductConfigurationFromQuoteItemToShoppingListItem(
        ItemTransfer $itemTransfer,
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ShoppingListItemTransfer {
        $productConfigurationInstanceTransfer = $itemTransfer->getProductConfigurationInstance();

        if ($productConfigurationInstanceTransfer) {
            $shoppingListItemTransfer->setProductConfigurationInstance($productConfigurationInstanceTransfer);
        }

        return $shoppingListItemTransfer;
    }
}
