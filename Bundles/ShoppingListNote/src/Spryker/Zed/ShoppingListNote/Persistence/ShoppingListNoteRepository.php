<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListNote\Persistence;

use Generated\Shared\Transfer\ShoppingListItemNoteCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemNoteTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ShoppingListNote\Persistence\ShoppingListNotePersistenceFactory getFactory()
 */
class ShoppingListNoteRepository extends AbstractRepository implements ShoppingListNoteRepositoryInterface
{
    /**
     * @param int $idShoppingListItem
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemNoteTransfer|null
     */
    public function findShoppingListItemNoteByFkShoppingListItem(int $idShoppingListItem): ?ShoppingListItemNoteTransfer
    {
        $shoppingListItemNote = $this->getFactory()
            ->createShoppingListItemNoteQuery()
            ->filterByFkShoppingListItem($idShoppingListItem)
            ->findOne();

        if ($shoppingListItemNote !== null) {
            return $this->getFactory()
                ->createShoppingListItemNoteMapper()
                ->mapShoppingListItemNoteTransfer($shoppingListItemNote, new ShoppingListItemNoteTransfer());
        }

        return null;
    }

    /**
     * @param int[] $shoppingListItemIds
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemNoteCollectionTransfer
     */
    public function getShoppingListItemNoteCollectionByShoppingListItemIds(array $shoppingListItemIds): ShoppingListItemNoteCollectionTransfer
    {
        $shoppingListItemNoteEntities = $this->getFactory()
            ->createShoppingListItemNoteQuery()
            ->filterByFkShoppingListItem_In($shoppingListItemIds)
            ->find();

        return $this->getFactory()
            ->createShoppingListItemNoteMapper()
            ->mapShoppingListItemEntityCollectionToCollectionTransfer(
                $shoppingListItemNoteEntities,
                new ShoppingListItemNoteCollectionTransfer()
            );
    }
}
