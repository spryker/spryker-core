<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListNote\Business;

use Generated\Shared\Transfer\ShoppingListItemNoteTransfer;

interface ShoppingListNoteFacadeInterface
{
    /**
     * @api
     *
     * @param int $idShoppingListItem
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemNoteTransfer|null
     */
    public function getShoppingListItemNoteByIdShoppingListItem(int $idShoppingListItem): ?ShoppingListItemNoteTransfer;

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemNoteTransfer|null
     */
    public function saveShoppingListItemNote(ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer): ?ShoppingListItemNoteTransfer;

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer
     *
     * @return void
     */
    public function deleteShoppingListItemNote(ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer): void;
}
