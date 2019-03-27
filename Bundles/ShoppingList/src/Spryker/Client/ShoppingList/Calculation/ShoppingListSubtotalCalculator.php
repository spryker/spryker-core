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
     * @var \Spryker\Client\ShoppingListExtension\Dependency\Plugin\ShoppingListItemSubtotalPriceExpanderPluginInterface[]
     */
    protected $shoppingListItemSubtotalPriceExpanderPlugins;

    /**
     * @param array $shoppingListItemPriceExpanderSubtotalCalculatorPlugins
     */
    public function __construct(array $shoppingListItemPriceExpanderSubtotalCalculatorPlugins)
    {
        $this->shoppingListItemSubtotalPriceExpanderPlugins = $shoppingListItemPriceExpanderSubtotalCalculatorPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer[] $shoppingListItemProductViewTransfers
     *
     * @return int
     */
    public function calculateShoppingListSubtotal(array $shoppingListItemProductViewTransfers): int
    {
        $shoppingListSubtotal = 0;
        foreach ($shoppingListItemProductViewTransfers as $shoppingListItemProductViewTransfer) {
            if (!$shoppingListItemProductViewTransfer->getPrice()
                || !$shoppingListItemProductViewTransfer->getQuantity()
                || !$shoppingListItemProductViewTransfer->getAvailable()
            ) {
                continue;
            }

            $shoppingListSubtotal += $this->executeShoppingListItemSubtotalPriceExpanderPlugins(
                $shoppingListItemProductViewTransfer,
                $shoppingListItemProductViewTransfer->getPrice() * $shoppingListItemProductViewTransfer->getQuantity()
            );
        }

        return $shoppingListSubtotal;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $shoppingListItemProductViewTransfer
     * @param int $calculatedShoppingListItemSubtotal
     *
     * @return int
     */
    protected function executeShoppingListItemSubtotalPriceExpanderPlugins(
        ProductViewTransfer $shoppingListItemProductViewTransfer,
        int $calculatedShoppingListItemSubtotal
    ): int {
        foreach ($this->shoppingListItemSubtotalPriceExpanderPlugins as $shoppingListItemSubtotalPriceExpanderPlugin) {
            $calculatedShoppingListItemSubtotal = $shoppingListItemSubtotalPriceExpanderPlugin->expandShoppingListItemSubtotal(
                $shoppingListItemProductViewTransfer,
                $calculatedShoppingListItemSubtotal
            );
        }

        return $calculatedShoppingListItemSubtotal;
    }
}
