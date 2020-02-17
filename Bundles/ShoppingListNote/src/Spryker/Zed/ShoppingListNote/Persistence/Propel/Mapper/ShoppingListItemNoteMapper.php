<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListNote\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ShoppingListItemNoteCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemNoteTransfer;
use Orm\Zed\ShoppingListNote\Persistence\SpyShoppingListItemNote;
use Propel\Runtime\Collection\ObjectCollection;

class ShoppingListItemNoteMapper implements ShoppingListItemNoteMapperInterface
{
    /**
     * @param \Orm\Zed\ShoppingListNote\Persistence\SpyShoppingListItemNote $shoppingListItemNote
     * @param \Generated\Shared\Transfer\ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemNoteTransfer
     */
    public function mapShoppingListItemNoteTransfer(
        SpyShoppingListItemNote $shoppingListItemNote,
        ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer
    ): ShoppingListItemNoteTransfer {
        $shoppingListItemNoteTransfer->fromArray($shoppingListItemNote->toArray(), true);

        return $shoppingListItemNoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer
     * @param \Orm\Zed\ShoppingListNote\Persistence\SpyShoppingListItemNote $shoppingListItemNoteEntity
     *
     * @return \Orm\Zed\ShoppingListNote\Persistence\SpyShoppingListItemNote
     */
    public function mapShoppingListItemNoteTransferToEntity(
        ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer,
        SpyShoppingListItemNote $shoppingListItemNoteEntity
    ): SpyShoppingListItemNote {
        $shoppingListItemNoteEntity->fromArray($shoppingListItemNoteTransfer->modifiedToArray());

        return $shoppingListItemNoteEntity;
    }

    /**
     * @param \Orm\Zed\ShoppingListNote\Persistence\SpyShoppingListItemNote[]|\Propel\Runtime\Collection\ObjectCollection $shoppingListItemEntityCollection
     * @param \Generated\Shared\Transfer\ShoppingListItemNoteCollectionTransfer $shoppingListItemNoteCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemNoteCollectionTransfer
     */
    public function mapShoppingListItemEntityCollectionToCollectionTransfer(
        ObjectCollection $shoppingListItemEntityCollection,
        ShoppingListItemNoteCollectionTransfer $shoppingListItemNoteCollectionTransfer
    ): ShoppingListItemNoteCollectionTransfer {
        foreach ($shoppingListItemEntityCollection as $shoppingListItemNoteEntity) {
            $shoppingListItemNoteTransfer = $this
                ->mapShoppingListItemNoteTransfer($shoppingListItemNoteEntity, new ShoppingListItemNoteTransfer());

            $shoppingListItemNoteCollectionTransfer->addNote($shoppingListItemNoteTransfer);
        }

        return $shoppingListItemNoteCollectionTransfer;
    }
}
