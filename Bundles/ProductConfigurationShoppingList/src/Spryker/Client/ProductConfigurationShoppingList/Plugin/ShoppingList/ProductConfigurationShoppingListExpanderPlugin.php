<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationShoppingList\Plugin\ShoppingList;

use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ShoppingListExtension\Dependency\Plugin\ShoppingListExpanderPluginInterface;

/**
 * @method \Spryker\Client\ProductConfigurationShoppingList\ProductConfigurationShoppingListClientInterface getClient()
 */
class ProductConfigurationShoppingListExpanderPlugin extends AbstractPlugin implements ShoppingListExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `ShoppingListTransfer.shoppingListItem.sku` to be provided.
     * - Expands shopping list items with product configuration.
     * - Finds product configuration by sku.
     * - Sets configuration to `ShoppingListTransfer.shoppingListItemTransfer.productConfigurationInstance`.
     * - Sets encoded configuration to `ShoppingListTransfer.shoppingListItemTransfer.productConfigurationInstanceData`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function expand(ShoppingListTransfer $shoppingListTransfer): ShoppingListTransfer
    {
        return $this->getClient()->expandShoppingListItemsWithProductConfiguration($shoppingListTransfer);
    }
}
