<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListNote\Persistence;

use Generated\Shared\Transfer\ShoppingListItemNoteTransfer;

interface ShoppingListNoteEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemNoteTransfer
     */
    public function saveShoppingListItemNote(ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer): ShoppingListItemNoteTransfer;

    /**
     * @param int $idShoppingListItemNote
     *
     * @return void
     */
    public function deleteShoppingListItemNoteById(int $idShoppingListItemNote): void;
}
