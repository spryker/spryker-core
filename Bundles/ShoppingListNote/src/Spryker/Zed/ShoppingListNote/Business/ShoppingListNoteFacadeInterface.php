<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListNote\Business;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShoppingListItemNoteTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;

interface ShoppingListNoteFacadeInterface
{
    /**
     * Specification:
     *  - Gets shopping list item note by shopping list item id.
     *
     * @api
     *
     * @param int $idShoppingListItem
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemNoteTransfer
     */
    public function getShoppingListItemNoteByIdShoppingListItem(int $idShoppingListItem): ShoppingListItemNoteTransfer;

    /**
     * Specification:
     *  - Deletes note from shopping list item by id.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer
     *
     * @return void
     */
    public function deleteShoppingListItemNote(ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer): void;

    /**
     * Specification:
     * - Saves current state of shopping list item note, if the note is empty, it gets deleted.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function saveShoppingListItemNoteForShoppingListItem(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer;

    /**
     * Specification:
     *  - Expands shopping list item with additional parameters.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function expandShoppingListItem(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer;

    /**
     * Specification:
     * - Copies the item cart note to shopping list item note if not empty.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function mapItemCartNoteToShoppingListItemNote(ItemTransfer $itemTransfer, ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer;
}
