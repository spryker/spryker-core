<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListProductOptionConnector\ProductOptionSubtotal;

use Generated\Shared\Transfer\ProductViewTransfer;

interface ProductOptionSubtotalCalculatorInterface
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
    ): int;
}
