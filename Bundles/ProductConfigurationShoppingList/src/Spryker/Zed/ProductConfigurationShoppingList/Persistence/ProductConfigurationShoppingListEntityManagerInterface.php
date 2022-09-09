<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationShoppingList\Persistence;

use Generated\Shared\Transfer\ShoppingListItemTransfer;

interface ProductConfigurationShoppingListEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return void
     */
    public function updateProductConfigurationData(ShoppingListItemTransfer $shoppingListItemTransfer): void;
}
