<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingList\Calculation;

interface ShoppingListSubtotalCalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer[] $shoppingListItemProductViewTransfers
     *
     * @return int
     */
    public function calculateShoppingListSubtotal(array $shoppingListItemProductViewTransfers): int;
}
