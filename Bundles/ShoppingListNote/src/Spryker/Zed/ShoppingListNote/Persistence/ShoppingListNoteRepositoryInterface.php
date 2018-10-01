<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListNote\Persistence;

use Generated\Shared\Transfer\ShoppingListItemNoteTransfer;

interface ShoppingListNoteRepositoryInterface
{
    /**
     * @param int $idShoppingListItem
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemNoteTransfer|null
     */
    public function findShoppingListItemNoteByFkShoppingListItem(int $idShoppingListItem): ?ShoppingListItemNoteTransfer;
}
