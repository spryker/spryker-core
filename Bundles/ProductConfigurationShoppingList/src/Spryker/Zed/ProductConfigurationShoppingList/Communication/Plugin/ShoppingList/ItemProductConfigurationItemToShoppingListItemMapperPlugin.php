<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationShoppingList\Communication\Plugin\ShoppingList;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ItemToShoppingListItemMapperPluginInterface;

/**
 * @method \Spryker\Zed\ProductConfigurationShoppingList\Business\ProductConfigurationShoppingListFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductConfigurationShoppingList\ProductConfigurationShoppingListConfig getConfig()
 */
class ItemProductConfigurationItemToShoppingListItemMapperPlugin extends AbstractPlugin implements ItemToShoppingListItemMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Copies product configuration from cart item to shopping list item.
     * - Copies `ItemTransfer.productConfigurationInstance` to `ShoppingListItemTransfer.productConfigurationInstance`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function map(ItemTransfer $itemTransfer, ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        return $this->getFacade()->copyProductConfigurationFromQuoteItemToShoppingListItem($itemTransfer, $shoppingListItemTransfer);
    }
}
