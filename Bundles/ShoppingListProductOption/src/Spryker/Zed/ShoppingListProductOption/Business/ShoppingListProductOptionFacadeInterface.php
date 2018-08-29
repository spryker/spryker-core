<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOption\Business;

use Generated\Shared\Transfer\ProductOptionCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;

interface ShoppingListProductOptionFacadeInterface
{
    /**
     * Specification:
     * - Removes existing shopping list product options.
     * - Creates new shopping list product options.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return void
     */
    public function saveShoppingListItemProductOptions(ShoppingListItemTransfer $shoppingListItemTransfer): void;

    /**
     * Specification:
     * - Finds shopping list product options for shopping list item.
     *
     * @api
     *
     * @param int $idShoppingListItem
     *
     * @return \Generated\Shared\Transfer\ProductOptionCollectionTransfer
     */
    public function getShoppingListItemProductOptionsByIdShoppingListItem(int $idShoppingListItem): ProductOptionCollectionTransfer;

    /**
     * Specification:
     * - Adds a product options to shopping list item.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function expandItem(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer;
}
