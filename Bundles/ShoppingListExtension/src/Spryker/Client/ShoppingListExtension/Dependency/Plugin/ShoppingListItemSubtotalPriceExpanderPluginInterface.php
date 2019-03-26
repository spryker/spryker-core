<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductViewTransfer;

interface ShoppingListItemSubtotalPriceExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands shopping list item subtotal price.
     * - Returns final shopping list item subtotal price.
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
    ): int;
}
