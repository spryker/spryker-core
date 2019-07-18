<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListNote\Persistence;

use Generated\Shared\Transfer\ShoppingListItemNoteTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ShoppingListNote\Persistence\ShoppingListNotePersistenceFactory getFactory()
 */
class ShoppingListNoteEntityManager extends AbstractEntityManager implements ShoppingListNoteEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemNoteTransfer
     */
    public function saveShoppingListItemNote(ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer): ShoppingListItemNoteTransfer
    {
        $shoppingListItemNoteEntity = $this->getFactory()
            ->createShoppingListItemNoteQuery()
            ->filterByIdShoppingListItemNote($shoppingListItemNoteTransfer->getIdShoppingListItemNote())
            ->findOneOrCreate();

        $shoppingListItemNoteEntity = $this->getFactory()
            ->createShoppingListItemNoteMapper()
            ->mapShoppingListItemNoteTransferToEntity($shoppingListItemNoteTransfer, $shoppingListItemNoteEntity);

        $shoppingListItemNoteEntity->save();
        $shoppingListItemNoteTransfer->setIdShoppingListItemNote($shoppingListItemNoteEntity->getIdShoppingListItemNote());

        return $shoppingListItemNoteTransfer;
    }

    /**
     * @param int $idShoppingListItemNote
     *
     * @return void
     */
    public function deleteShoppingListItemNoteById(int $idShoppingListItemNote): void
    {
        $this->getFactory()
            ->createShoppingListItemNoteQuery()
            ->filterByIdShoppingListItemNote($idShoppingListItemNote)
            ->delete();
    }
}
