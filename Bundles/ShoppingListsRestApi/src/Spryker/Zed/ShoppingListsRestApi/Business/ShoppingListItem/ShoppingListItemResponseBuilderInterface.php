<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem;

use Generated\Shared\Transfer\RestShoppingListItemResponseTransfer;

interface ShoppingListItemResponseBuilderInterface
{
    /**
     * @return \Generated\Shared\Transfer\RestShoppingListItemResponseTransfer
     */
    public function createRestShoppingListItemResponseTransfer(): RestShoppingListItemResponseTransfer;

    /**
     * @return \Generated\Shared\Transfer\RestShoppingListItemResponseTransfer
     */
    public function createShoppingListNotFoundErrorResponse(): RestShoppingListItemResponseTransfer;

    /**
     * @return \Generated\Shared\Transfer\RestShoppingListItemResponseTransfer
     */
    public function createCompanyUserNotFoundErrorResponse(): RestShoppingListItemResponseTransfer;

    /**
     * @return \Generated\Shared\Transfer\RestShoppingListItemResponseTransfer
     */
    public function createShoppingListCanNotAddItemErrorResponse(): RestShoppingListItemResponseTransfer;
}
