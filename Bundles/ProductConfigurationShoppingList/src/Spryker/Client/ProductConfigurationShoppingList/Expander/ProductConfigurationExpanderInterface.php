<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationShoppingList\Expander;

use Generated\Shared\Transfer\ShoppingListTransfer;

interface ProductConfigurationExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function expandShoppingListItemsWithProductConfiguration(ShoppingListTransfer $shoppingListTransfer): ShoppingListTransfer;
}
