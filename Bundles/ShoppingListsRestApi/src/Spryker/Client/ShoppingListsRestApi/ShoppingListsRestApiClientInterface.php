<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListsRestApi;

use Generated\Shared\Transfer\RestShoppingListItemRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemResponseTransfer;

interface ShoppingListsRestApiClientInterface
{
    /**
     * Specification:
     *  - Retrieves company user by uuid.
     *  - Checks that company user belongs to current customer.
     *  - Retrieves shopping list by uuid.
     *  - Adds items to shopping list.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function addItem(
        RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
    ): ShoppingListItemResponseTransfer;
}
