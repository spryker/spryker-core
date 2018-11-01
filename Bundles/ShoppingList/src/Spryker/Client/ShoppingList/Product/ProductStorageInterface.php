<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingList\Product;

use Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer;

interface ProductStorageInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer $shoppingListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer
     */
    public function expandProductDetails(ShoppingListOverviewResponseTransfer $shoppingListResponseTransfer): ShoppingListOverviewResponseTransfer;
}
