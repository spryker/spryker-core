<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListProductOptionConnector\ProductOptionSubtotal;

use ArrayObject;
use Generated\Shared\Transfer\ProductViewTransfer;

class ProductOptionSubtotalCalculator implements ProductOptionSubtotalCalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $shoppingListItemProductViewTransfer
     * @param int $calculatedShoppingListItemSubtotal
     *
     * @return int
     */
    public function expandShoppingListItemSubtotalWithProductOptions(
        ProductViewTransfer $shoppingListItemProductViewTransfer,
        int $calculatedShoppingListItemSubtotal
    ): int {
        $shoppingListItemTransfer = $shoppingListItemProductViewTransfer->getShoppingListItem();

        if (!$shoppingListItemTransfer || !$shoppingListItemTransfer->getProductOptions()->count()) {
            return $calculatedShoppingListItemSubtotal;
        }

        return $calculatedShoppingListItemSubtotal + $this->calculateProductOptionsSubtotal($shoppingListItemTransfer->getProductOptions());
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductOptionTransfer[] $shoppingListItemProductOptionTransfers
     *
     * @return int
     */
    protected function calculateProductOptionsSubtotal(ArrayObject $shoppingListItemProductOptionTransfers): int
    {
        $productOptionsSubtotal = 0;
        foreach ($shoppingListItemProductOptionTransfers as $shoppingListItemProductOptionTransfer) {
            if (!$shoppingListItemProductOptionTransfer->getUnitGrossPrice()) {
                continue;
            }

            $productOptionsSubtotal += $shoppingListItemProductOptionTransfer->getUnitGrossPrice();
        }

        return $productOptionsSubtotal;
    }
}
