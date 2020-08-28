<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListNote\Persistence;

use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemNoteTransfer;
use Orm\Zed\ShoppingListNote\Persistence\SpyShoppingListItemNote;
use Propel\Runtime\Collection\ObjectCollection;
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

    /**
     * @param int[] $shoppingListItemNoteIds
     *
     * @return void
     */
    public function deleteShoppingListItemNoteByShoppingListItemNoteIds(array $shoppingListItemNoteIds): void
    {
        $this->getFactory()
            ->createShoppingListItemNoteQuery()
            ->filterByIdShoppingListItemNote_In($shoppingListItemNoteIds)
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function saveShoppingListItemNoteInBulk(ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer): ShoppingListItemCollectionTransfer
    {
        $shoppingListItemNoteObjectCollection = new ObjectCollection();
        $shoppingListItemNoteObjectCollection->setModel(SpyShoppingListItemNote::class);
        foreach ($shoppingListItemCollectionTransfer->getItems() as $shoppingListItemTransfer) {
            $shoppingListItemNoteTransfer = $shoppingListItemTransfer->getShoppingListItemNote();
            if (!$shoppingListItemNoteTransfer || !$shoppingListItemNoteTransfer->getNote()) {
                continue;
            }

            $shoppingListItemNoteTransfer->setFkShoppingListItem($shoppingListItemTransfer->getIdShoppingListItem());

            $shoppingListItemNoteEntity = $this->getFactory()
                ->createShoppingListItemNoteMapper()
                ->mapShoppingListItemNoteTransferToEntity($shoppingListItemNoteTransfer, new SpyShoppingListItemNote());

            // Prevent primary key duplication error
            if ($shoppingListItemNoteEntity->getIdShoppingListItemNote()) {
                $shoppingListItemNoteEntity->setNew(false);
            }

            $shoppingListItemNoteObjectCollection->append($shoppingListItemNoteEntity);
        }

        $shoppingListItemNoteObjectCollection->save();

        return $this->getFactory()
            ->createShoppingListItemNoteMapper()
            ->mapShoppingListItemNoteEntityCollectionToShoppingListItemCollectionTransfer(
                $shoppingListItemNoteObjectCollection,
                $shoppingListItemCollectionTransfer
            );
    }
}
