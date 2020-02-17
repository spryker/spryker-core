<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListNote\Business\ShoppingListItemNote;

use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemNoteCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemNoteTransfer;

interface ShoppingListItemNoteReaderInterface
{
    /**
     * @param int $idShoppingListItem
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemNoteTransfer
     */
    public function getShoppingListItemNoteByIdShoppingListItem(int $idShoppingListItem): ShoppingListItemNoteTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemNoteCollectionTransfer
     */
    public function getShoppingListItemNoteCollectionByShoppingListItemCollection(ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer): ShoppingListItemNoteCollectionTransfer;
}
