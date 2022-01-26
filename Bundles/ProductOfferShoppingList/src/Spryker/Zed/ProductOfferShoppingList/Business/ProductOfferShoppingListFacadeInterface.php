<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShoppingList\Business;

use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer;

interface ProductOfferShoppingListFacadeInterface
{
    /**
     * Specification:
     * - Checks if product offer exists and refers to required product.
     * - Checks if product offer is active.
     * - Checks if product offer approval status is 'approved'.
     * - Sets `ShoppingListPreAddItemCheckResponseTransfer.isSuccess=false` and error messages when check failed.
     * - Sets `ShoppingListPreAddItemCheckResponseTransfer.isSuccess=true` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer
     */
    public function checkProductOfferShoppingListItem(
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ShoppingListPreAddItemCheckResponseTransfer;
}
