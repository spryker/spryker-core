<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListNote\Persistence\Propel\Mapper;

use ArrayObject;
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
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemNoteTransfer[]|\ArrayObject
     */
    public function mapShoppingListItemEntityCollectionToCollectionTransfer(ObjectCollection $shoppingListItemEntityCollection): ArrayObject
    {
        $shoppingListItemNoteTransfers = new ArrayObject();

        foreach ($shoppingListItemEntityCollection as $shoppingListItemNoteEntity) {
            $shoppingListItemNoteTransfer = $this
                ->mapShoppingListItemNoteTransfer($shoppingListItemNoteEntity, new ShoppingListItemNoteTransfer());

            $shoppingListItemNoteTransfers->append($shoppingListItemNoteTransfer);
        }

        return $shoppingListItemNoteTransfers;
    }
}
