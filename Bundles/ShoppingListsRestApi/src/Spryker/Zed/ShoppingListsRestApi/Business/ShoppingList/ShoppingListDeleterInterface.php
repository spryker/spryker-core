<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList;

use Generated\Shared\Transfer\RestShoppingListRequestTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;

interface ShoppingListDeleterInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestShoppingListRequestTransfer $restShoppingListRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function deleteShoppingList(
        RestShoppingListRequestTransfer $restShoppingListRequestTransfer
    ): ShoppingListResponseTransfer;
}
