<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationShoppingList;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;

interface ProductConfigurationShoppingListClientInterface
{
    /**
     * Specification:
     * - Requires `ShoppingListItemTransfer.sku` to be provided.
     * - Finds product configuration for given shopping list item SKU.
     * - Adds product configuration to shopping list item if product configuration found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function addProductConfigurationToShoppingListItem(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer;

    /**
     * Specification:
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
    public function copyProductConfigurationFromShoppingListItemToQuoteItem(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ItemTransfer $itemTransfer
    ): ItemTransfer;

    /**
     * Specification:
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
    public function expandShoppingListItemsWithProductConfiguration(ShoppingListTransfer $shoppingListTransfer): ShoppingListTransfer;

    /**
     * Specification:
     * - Requires `ProductConfiguratorRequestTransfer.productConfiguratorRequestData` to be set.
     * - Requires `ProductConfiguratorRequestTransfer.productConfiguratorRequestData.shoppingListItemUuid` to be set.
     * - Maps raw product configurator checksum response.
     * - Validates product configurator checksum response.
     * - Updates shoppingList item product configuration.
     * - Returns `ProductConfiguratorResponseProcessorResponseTransfer.isSuccessful` equal to `true` when response was processed.
     * - Returns `ProductConfiguratorResponseProcessorResponseTransfer.isSuccessful` equal to `false` when something went wrong.
     * - Returns `ProductConfiguratorResponseProcessorResponseTransfer.messages` containing error messages, if any was added.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     * @param array<string, mixed> $configuratorResponseData
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    public function processProductConfiguratorCheckSumResponse(
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer,
        array $configuratorResponseData
    ): ProductConfiguratorResponseProcessorResponseTransfer;

    /**
     * Specification:
     * - Requires `ProductConfiguratorRequestTransfer.productConfiguratorRequestData.shoppingListItemUuid` to be set.
     * - Finds product configuration instance for given shoppingList item.
     * - Maps product configuration instance data to `ProductConfiguratorRequestTransfer`.
     * - Sends product configurator access token request.
     * - Returns `ProductConfiguratorRedirectTransfer.isSuccessful` equal to `true` when redirect URL was successfully resolved.
     * - Returns `ProductConfiguratorRedirectTransfer.isSuccessful` equal to `false` otherwise.
     * - Returns `ProductConfiguratorRedirectTransfer.messages` with errors if any exist.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer
     */
    public function resolveProductConfiguratorAccessTokenRedirect(
        ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
    ): ProductConfiguratorRedirectTransfer;
}
