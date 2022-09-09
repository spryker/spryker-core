<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationShoppingList\Communication\Plugin\ShoppingList;

use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShoppingListExtension\Dependency\Plugin\AddItemPreCheckPluginInterface;

/**
 * @method \Spryker\Zed\ProductConfigurationShoppingList\Business\ProductConfigurationShoppingListFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductConfigurationShoppingList\ProductConfigurationShoppingListConfig getConfig()
 */
class ProductConfigurationShoppingListAddItemPreCheckPlugin extends AbstractPlugin implements AddItemPreCheckPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `ShoppingListItemTransfer.sku` to be set.
     * - Checks if product configuration exists by provided `ShoppingListItem.sku` transfer property.
     * - Returns `ShoppingListPreAddItemCheckResponseTransfer.success=true` if product configuration is found, sets `ShoppingListPreAddItemCheckResponseTransfer.success=false` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer
     */
    public function check(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListPreAddItemCheckResponseTransfer
    {
        return $this->getFacade()->checkShoppingListItemProductConfiguration($shoppingListItemTransfer);
    }
}
