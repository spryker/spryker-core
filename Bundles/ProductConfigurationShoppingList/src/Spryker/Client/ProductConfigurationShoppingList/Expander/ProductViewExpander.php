<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationShoppingList\Expander;

use Generated\Shared\Transfer\ProductViewTransfer;

class ProductViewExpander implements ProductViewExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewAvailability(ProductViewTransfer $productViewTransfer): ProductViewTransfer
    {
        $productConfigurationInstance = $productViewTransfer->getProductConfigurationInstance();
        $shoppingListItem = $productViewTransfer->getShoppingListItem();

        if ($productConfigurationInstance === null || $shoppingListItem === null) {
            return $productViewTransfer;
        }

        if ($shoppingListItem->getQuantity() > $productConfigurationInstance->getAvailableQuantity()) {
            $productViewTransfer->setAvailable(false);
        }

        return $productViewTransfer;
    }
}
