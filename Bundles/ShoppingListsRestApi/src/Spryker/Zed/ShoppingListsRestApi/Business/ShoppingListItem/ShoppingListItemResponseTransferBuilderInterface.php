<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem;

use Generated\Shared\Transfer\RestShoppingListItemResponseTransfer;

interface ShoppingListItemResponseTransferBuilderInterface
{
    /**
     * @return \Generated\Shared\Transfer\RestShoppingListItemResponseTransfer
     */
    public function createRestShoppingListItemResponseTransfer(): RestShoppingListItemResponseTransfer;

    /**
     * @return \Generated\Shared\Transfer\RestShoppingListItemResponseTransfer
     */
    public function createShoppingListNotFoundErrorResponseTransfer(): RestShoppingListItemResponseTransfer;

    /**
     * @return \Generated\Shared\Transfer\RestShoppingListItemResponseTransfer
     */
    public function createCompanyUserNotFoundErrorResponseTransfer(): RestShoppingListItemResponseTransfer;

    /**
     * @return \Generated\Shared\Transfer\RestShoppingListItemResponseTransfer
     */
    public function createShoppingListCanNotAddItemErrorResponseTransfer(): RestShoppingListItemResponseTransfer;
}
