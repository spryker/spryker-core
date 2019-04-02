<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingList\Calculation;

use Generated\Shared\Transfer\ProductViewTransfer;

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

            if (!$this->isShoppingListItemAvailable($shoppingListItemProductView)) {
                continue;
            }

            $shoppingListSubtotal += $shoppingListItemProductView->getCurrentProductPrice()->getSumPrice();
        }

        return $shoppingListSubtotal;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $shoppingListItemProductView
     *
     * @return bool
     */
    protected function isShoppingListItemAvailable(ProductViewTransfer $shoppingListItemProductView): bool
    {
        return $shoppingListItemProductView->getAvailable() === true;
    }
}
