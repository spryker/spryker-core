<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationShoppingList\Communication\Plugin\ShoppingList;

use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ShoppingListItemBulkPostSavePluginInterface;

/**
 * @method \Spryker\Zed\ProductConfigurationShoppingList\Business\ProductConfigurationShoppingListFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductConfigurationShoppingList\ProductConfigurationShoppingListConfig getConfig()
 */
class ProductConfigurationShoppingListItemBulkPostSavePlugin extends AbstractPlugin implements ShoppingListItemBulkPostSavePluginInterface
{
    /**
     * {@inheritDoc}
     * - Updates product configuration of shopping list items.
     * - Prepares product configuration data attached to a shopping list item to be saved.
     * - Sets encoded data to `ShoppingListItemTransfer.productConfigurationInstanceData` property.
     * - Expects `ShoppingListItemTransfer.uuid` to be provided.
     * - Removes configuration if product configuration instance is not set at shopping list item.
     * - Saves JSON encoded product configuration instance to shopping list item otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function execute(
        ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
    ): ShoppingListItemCollectionTransfer {
        return $this->getFacade()->updateProductConfigurations($shoppingListItemCollectionTransfer);
    }
}
