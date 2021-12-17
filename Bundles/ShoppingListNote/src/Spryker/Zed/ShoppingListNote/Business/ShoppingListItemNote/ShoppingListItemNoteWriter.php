<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListNote\Business\ShoppingListItemNote;

use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemNoteTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Zed\ShoppingListNote\Persistence\ShoppingListNoteEntityManagerInterface;

class ShoppingListItemNoteWriter implements ShoppingListItemNoteWriterInterface
{
    /**
     * @var \Spryker\Zed\ShoppingListNote\Persistence\ShoppingListNoteEntityManagerInterface
     */
    protected $shoppingListNoteEntityManager;

    /**
     * @param \Spryker\Zed\ShoppingListNote\Persistence\ShoppingListNoteEntityManagerInterface $shoppingListNoteEntityManager
     */
    public function __construct(ShoppingListNoteEntityManagerInterface $shoppingListNoteEntityManager)
    {
        $this->shoppingListNoteEntityManager = $shoppingListNoteEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer
     *
     * @return void
     */
    public function deleteShoppingListItemNoteById(ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer): void
    {
        $this->deleteShoppingListItemNoteTransfer($shoppingListItemNoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function saveShoppingListItemNoteForShoppingListItem(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        $shoppingListItemNote = $shoppingListItemTransfer->getShoppingListItemNote();

        if (!$shoppingListItemNote) {
            return $shoppingListItemTransfer;
        }

        $shoppingListItemNote->setFkShoppingListItem($shoppingListItemTransfer->getIdShoppingListItem());
        $this->saveShoppingListItemNoteTransfer($shoppingListItemNote);

        return $shoppingListItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function saveShoppingListItemNoteForShoppingListItemBulk(
        ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
    ): ShoppingListItemCollectionTransfer {
        return $this->saveShoppingListItemNoteTransfersInBulk($shoppingListItemCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemNoteTransfer|null
     */
    protected function saveShoppingListItemNoteTransfer(ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer): ?ShoppingListItemNoteTransfer
    {
        if (!$shoppingListItemNoteTransfer->getNote()) {
            $this->deleteShoppingListItemNoteTransfer($shoppingListItemNoteTransfer);

            return null;
        }

        return $this->shoppingListNoteEntityManager->saveShoppingListItemNote($shoppingListItemNoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer
     *
     * @return void
     */
    protected function deleteShoppingListItemNoteTransfer(ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer): void
    {
        if ($shoppingListItemNoteTransfer->getIdShoppingListItemNote()) {
            $this->shoppingListNoteEntityManager->deleteShoppingListItemNoteById($shoppingListItemNoteTransfer->getIdShoppingListItemNote());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return void
     */
    protected function deleteShoppingListItemNotesWithoutNoteValueInBulk(ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer): void
    {
        $shoppingListItemNoteIds = [];
        foreach ($shoppingListItemCollectionTransfer->getItems() as $shoppingListItemTransfer) {
            $shoppingListItemNoteTransfer = $shoppingListItemTransfer->getShoppingListItemNote();
            if (!$shoppingListItemNoteTransfer || $shoppingListItemNoteTransfer->getNote() || !$shoppingListItemNoteTransfer->getIdShoppingListItemNote()) {
                continue;
            }

            $shoppingListItemNoteIds[] = $shoppingListItemNoteTransfer->getIdShoppingListItemNote();
        }

        $this->shoppingListNoteEntityManager->deleteShoppingListItemNoteByShoppingListItemNoteIds($shoppingListItemNoteIds);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    protected function saveShoppingListItemNoteTransfersInBulk(
        ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
    ): ShoppingListItemCollectionTransfer {
        $this->deleteShoppingListItemNotesWithoutNoteValueInBulk($shoppingListItemCollectionTransfer);

        return $this->shoppingListNoteEntityManager->saveShoppingListItemNoteInBulk($shoppingListItemCollectionTransfer);
    }
}
