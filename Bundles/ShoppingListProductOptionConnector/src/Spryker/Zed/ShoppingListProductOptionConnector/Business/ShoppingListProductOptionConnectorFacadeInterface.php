<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOptionConnector\Business;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;

interface ShoppingListProductOptionConnectorFacadeInterface
{
    /**
     * Specification:
     * - Removes existing shopping list product options from persistence.
     * - Creates new shopping list product options in persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function saveShoppingListItemProductOptions(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer;

    /**
     * Specification:
     * - Operates in bulk.
     * - Removes existing shopping list product options from persistence.
     * - Creates new shopping list product options in persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function saveShoppingListItemProductOptionsForShoppingListItemCollection(
        ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
    ): ShoppingListItemCollectionTransfer;

    /**
     * Specification:
     * - Removes existing shopping list product options from persistence.
     *
     * @api
     *
     * @param int $idShoppingListItem
     *
     * @return void
     */
    public function removeShoppingListItemProductOptions(int $idShoppingListItem): void;

    /**
     * Specification:
     * - Populates shopping list item with active and assigned product options.
     * - Sets `ProductOptionTransfer::$unitPrice` for each option based on `ShoppingListItemTransfer::$currencyIsoCode` and `ShoppingListItemTransfer::$priceMode` properties.
     * - Uses default store currency and price mode if not specified.
     *
     * @api
     *
     * @deprecated Use {@link expandShoppingListItemCollectionWithProductOptions()} instead.
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function expandShoppingListItemWithProductOptions(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer;

    /**
     * Specification:
     * - Populates shopping list item collection with active and assigned product options.
     * - Sets `ProductOptionTransfer::$unitPrice` for each option based on `ShoppingListItemTransfer::$currencyIsoCode` and `ShoppingListItemTransfer::$priceMode` properties.
     * - Uses default store currency and price mode if not specified.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function expandShoppingListItemCollectionWithProductOptions(
        ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
    ): ShoppingListItemCollectionTransfer;

    /**
     * Specification:
     * - Maps ItemTransfer product options to ShoppingListItemTransfer product options.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function mapCartItemProductOptionsToShoppingListItemProductOptions(
        ItemTransfer $itemTransfer,
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ShoppingListItemTransfer;

    /**
     * Specification:
     * - Removes deleted or deactivated product option values by ids from shopping list items.
     * - Product option values ids are taken from ProductOptionGroupTransfer::productOptionValuesToBeRemoved.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return void
     */
    public function deleteShoppingListItemProductOptionsByRemovedProductOptionValues(ProductOptionGroupTransfer $productOptionGroupTransfer): void;
}
