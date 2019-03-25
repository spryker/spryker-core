<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingList\Calculation;

class ShoppingListSubtotalCalculator implements ShoppingListSubtotalCalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer[] $shoppingListItemProductViewTransfers
     *
     * @return int
     */
    public function calculateShoppingListSubtotal(array $shoppingListItemProductViewTransfers): int
    {
        $shoppingListSubtotal = 0;
        foreach ($shoppingListItemProductViewTransfers as $shoppingListItemProductViewTransfer) {
            if (!$shoppingListItemProductViewTransfer->getPrice() || !$shoppingListItemProductViewTransfer->getQuantity()) {
                continue;
            }

            $shoppingListSubtotal += $shoppingListItemProductViewTransfer->getPrice() * $shoppingListItemProductViewTransfer->getQuantity();
        }

        return $shoppingListSubtotal;
    }
}
