<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingList\Calculation;

class ShoppingListSubtotalCalculator implements ShoppingListSubtotalCalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer[] $shoppingListItemProductViews
     *
     * @return int
     */
    public function calculateShoppingListSubtotal(array $shoppingListItemProductViews): int
    {
        $shoppingListSubtotal = 0;
        foreach ($shoppingListItemProductViews as $shoppingListItemProductView) {
            $shoppingListItemProductView->requireCurrentProductPrice();

            $shoppingListSubtotal += $shoppingListItemProductView->getCurrentProductPrice()->getSumPrice();
        }

        return $shoppingListSubtotal;
    }
}
