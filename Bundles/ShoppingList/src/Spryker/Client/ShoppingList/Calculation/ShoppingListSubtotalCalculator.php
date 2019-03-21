<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingList\Calculation;

class ShoppingListSubtotalCalculator implements ShoppingListSubtotalCalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer[] $shoppingListItems
     *
     * @return int
     */
    public function calculateShoppingListSubtotal(array $shoppingListItems): int
    {
        $shoppingListSubtotal = 0;
        foreach ($shoppingListItems as $shoppingListItem) {
            if (!$shoppingListItem->getPrice() || !$shoppingListItem->getQuantity()) {
                continue;
            }

            $shoppingListSubtotal += ($shoppingListItem->getPrice() * $shoppingListItem->getQuantity());
        }

        return $shoppingListSubtotal;
    }
}
