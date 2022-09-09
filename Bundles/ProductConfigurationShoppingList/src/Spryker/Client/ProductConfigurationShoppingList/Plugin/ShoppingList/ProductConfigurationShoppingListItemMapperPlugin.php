<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationShoppingList\Plugin\ShoppingList;

use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ShoppingListExtension\Dependency\Plugin\ShoppingListItemMapperPluginInterface;

/**
 * @method \Spryker\Client\ProductConfigurationShoppingList\ProductConfigurationShoppingListClientInterface getClient()
 */
class ProductConfigurationShoppingListItemMapperPlugin extends AbstractPlugin implements ShoppingListItemMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `ShoppingListItemTransfer.sku` to be provided.
     * - Finds product configuration for given shopping list item SKU.
     * - Adds product configuration to shopping list item if product configuration found.
     *
     * @api
     *
     * @param array<string, mixed> $params
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function map(array $params, ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        return $this->getClient()->addProductConfigurationToShoppingListItem($shoppingListItemTransfer);
    }
}
