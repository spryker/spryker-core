<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingList\Calculation;

class ShoppingListSubtotalCalculator implements ShoppingListSubtotalCalculatorInterface
{
    protected const KEY_PRICE = 'price';
    protected const KEY_QUANTITY = 'quantity';

    /**
     * @param array $shoppingListItems
     *
     * @return int
     */
    public function calculateShoppingListSubtotal(array $shoppingListItems): int
    {
        $shoppingListSubtotal = 0;
        foreach ($shoppingListItems as $shoppingListItem) {
            if (empty($shoppingListItem[static::KEY_PRICE] || empty($shoppingListItem[static::KEY_QUANTITY]))) {
                continue;
            }

            $shoppingListSubtotal += ($shoppingListItem[static::KEY_PRICE] * $shoppingListItem[static::KEY_QUANTITY]);
        }

        return $shoppingListSubtotal;
    }
}
