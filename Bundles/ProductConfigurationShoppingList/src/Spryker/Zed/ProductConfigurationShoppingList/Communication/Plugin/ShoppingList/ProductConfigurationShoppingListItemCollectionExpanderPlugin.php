<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationShoppingList\Communication\Plugin\ShoppingList;

use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ShoppingListItemCollectionExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductConfigurationShoppingList\Business\ProductConfigurationShoppingListFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductConfigurationShoppingList\ProductConfigurationShoppingListConfig getConfig()
 */
class ProductConfigurationShoppingListItemCollectionExpanderPlugin extends AbstractPlugin implements ShoppingListItemCollectionExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `ShoppingListItemTransfer` transfer object with product configuration data.
     * - Returns `ShoppingListItemCollectionTransfer` with expanded `ShoppingListItem` transfer objects.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function expandItemCollection(ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer): ShoppingListItemCollectionTransfer
    {
        return $this->getFacade()->expandShoppingListItemsWithProductConfiguration($shoppingListItemCollectionTransfer);
    }
}
