<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationShoppingList\Plugin\ShoppingList;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ShoppingListExtension\Dependency\Plugin\ShoppingListItemToItemMapperPluginInterface;

/**
 * @method \Spryker\Client\ProductConfigurationShoppingList\ProductConfigurationShoppingListClientInterface getClient()
 */
class ProductConfigurationShoppingListItemToItemMapperPlugin extends AbstractPlugin implements ShoppingListItemToItemMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Copies product configuration from shopping list item to cart item.
     * - Copies `ShoppingListItemTransfer.productConfigurationInstance` to `ItemTransfer.productConfigurationInstance`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function map(ShoppingListItemTransfer $shoppingListItemTransfer, ItemTransfer $itemTransfer): ItemTransfer
    {
        return $this->getClient()
            ->copyProductConfigurationFromShoppingListItemToQuoteItem($shoppingListItemTransfer, $itemTransfer);
    }
}
