<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationShoppingList\Plugin\ShoppingList;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductStorageExtension\Dependency\Plugin\ProductViewExpanderPluginInterface;

/**
 * @method \Spryker\Client\ProductConfigurationShoppingList\ProductConfigurationShoppingListClientInterface getClient()
 * @method \Spryker\Client\ProductConfigurationShoppingList\ProductConfigurationShoppingListFactory getFactory()
 */
class ProductConfigurationShoppingListProductAvailabilityExpanderPlugin implements ProductViewExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Sets product view unavailable if its product configuration quantity less than shopping list item quantity.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array<string, mixed> $productData
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewTransfer(ProductViewTransfer $productViewTransfer, array $productData, $localeName): ProductViewTransfer
    {
        return $this->getFactory()
            ->createProductViewExpander()
            ->expandProductViewAvailability($productViewTransfer);
    }
}
