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
            if (!$this->isShoppingListItemPriceIsDefined($shoppingListItemProductView)
                || !$this->isShoppingListItemQuantityIsDefined($shoppingListItemProductView)
                || !$this->isShoppingListItemAvailable($shoppingListItemProductView)
            ) {
                continue;
            }

            $shoppingListSubtotal += $shoppingListItemProductView->getPrice() * $shoppingListItemProductView->getQuantity();
        }

        return $shoppingListSubtotal;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $shoppingListItemProductView
     *
     * @return bool
     */
    protected function isShoppingListItemPriceIsDefined(ProductViewTransfer $shoppingListItemProductView): bool
    {
        return $shoppingListItemProductView->getPrice() !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $shoppingListItemProductView
     *
     * @return bool
     */
    protected function isShoppingListItemQuantityIsDefined(ProductViewTransfer $shoppingListItemProductView): bool
    {
        return $shoppingListItemProductView->getQuantity() !== null;
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
