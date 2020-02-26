<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator;

use Generated\Shared\Transfer\ShoppingListItemResponseTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;

interface ShoppingListItemOperationValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function invalidateItemAddResponse(
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): ShoppingListItemResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function validateItemAddRequest(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): ShoppingListItemResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     * @param \Generated\Shared\Transfer\ShoppingListResponseTransfer $shoppingListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function validateItemAddBulkRequest(
        ShoppingListTransfer $shoppingListTransfer,
        ShoppingListResponseTransfer $shoppingListResponseTransfer
    ): ShoppingListResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function validateItemDeleteRequest(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): ShoppingListItemResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function validateItemUpdateRequest(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): ShoppingListItemResponseTransfer;
}
