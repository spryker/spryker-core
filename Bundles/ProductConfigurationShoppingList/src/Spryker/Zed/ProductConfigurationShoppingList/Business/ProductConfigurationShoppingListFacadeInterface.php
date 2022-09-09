<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationShoppingList\Business;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer;

interface ProductConfigurationShoppingListFacadeInterface
{
    /**
     * Specification:
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
    public function checkShoppingListItemProductConfiguration(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListPreAddItemCheckResponseTransfer;

    /**
     * Specification:
     * - Expands `ShoppingListItemTransfer` transfer object with product configuration data.
     * - Returns `ShoppingListItemCollectionTransfer` with expanded `ShoppingListItem` transfer objects.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function expandShoppingListItemsWithProductConfiguration(
        ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
    ): ShoppingListItemCollectionTransfer;

    /**
     * Specification:
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
    public function updateProductConfigurations(
        ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
    ): ShoppingListItemCollectionTransfer;

    /**
     * Specification:
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
    public function copyProductConfigurationFromQuoteItemToShoppingListItem(
        ItemTransfer $itemTransfer,
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ShoppingListItemTransfer;
}
