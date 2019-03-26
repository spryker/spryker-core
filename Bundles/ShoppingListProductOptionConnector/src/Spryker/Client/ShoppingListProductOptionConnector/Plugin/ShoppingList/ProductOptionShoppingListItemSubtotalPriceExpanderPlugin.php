<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListProductOptionConnector\Plugin\ShoppingList;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ShoppingListExtension\Dependency\Plugin\ShoppingListItemSubtotalPriceExpanderPluginInterface;

/**
 * @method \Spryker\Client\ShoppingListProductOptionConnector\ShoppingListProductOptionConnectorFactory getFactory()
 */
class ProductOptionShoppingListItemSubtotalPriceExpanderPlugin extends AbstractPlugin implements ShoppingListItemSubtotalPriceExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     * - Expands shopping list item subtotal price with product options.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $shoppingListItemProductViewTransfer
     * @param int $calculatedShoppingListItemSubtotal
     *
     * @return int
     */
    public function expandShoppingListItemSubtotal(
        ProductViewTransfer $shoppingListItemProductViewTransfer,
        int $calculatedShoppingListItemSubtotal
    ): int {
        return $this->getFactory()
            ->createProductOptionSubtotalCalculator()
            ->expandShoppingListItemSubtotalWithProductOptions(
                $shoppingListItemProductViewTransfer,
                $calculatedShoppingListItemSubtotal
            );
    }
}
