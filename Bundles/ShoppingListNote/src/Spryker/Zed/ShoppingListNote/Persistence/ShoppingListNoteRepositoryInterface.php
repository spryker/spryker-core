<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListNote\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\ShoppingListItemNoteTransfer;

interface ShoppingListNoteRepositoryInterface
{
    /**
     * @param int $idShoppingListItem
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemNoteTransfer|null
     */
    public function findShoppingListItemNoteByFkShoppingListItem(int $idShoppingListItem): ?ShoppingListItemNoteTransfer;

    /**
     * @param array<int> $shoppingListItemIds
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ShoppingListItemNoteTransfer>
     */
    public function getShoppingListItemNoteTransfersByShoppingListItemIds(array $shoppingListItemIds): ArrayObject;
}
