<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListNote\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ShoppingListItemNoteTransfer;
use Generated\Shared\Transfer\SpyShoppingListItemNoteEntityTransfer;
use Orm\Zed\ShoppingListNote\Persistence\SpyShoppingListItemNote;

interface ShoppingListItemNoteMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListItemNoteEntityTransfer $shoppingListEntityTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemNoteTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemNoteTransfer
     */
    public function mapShoppingListItemNoteTransfer(
        SpyShoppingListItemNoteEntityTransfer $shoppingListEntityTransfer,
        ShoppingListItemNoteTransfer $shoppingListTransfer
    ): ShoppingListItemNoteTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer
     * @param \Orm\Zed\ShoppingListNote\Persistence\SpyShoppingListItemNote $shoppingListItemNoteEntity
     *
     * @return \Orm\Zed\ShoppingListNote\Persistence\SpyShoppingListItemNote
     */
    public function mapTransferToEntity(
        ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer,
        SpyShoppingListItemNote $shoppingListItemNoteEntity
    ): SpyShoppingListItemNote;
}
