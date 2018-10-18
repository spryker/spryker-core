<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListNote\Business\ShoppingListItemNote;

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
     * @param \Generated\Shared\Transfer\ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemNoteTransfer|null
     */
    protected function saveShoppingListItemNoteTransfer(ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer): ?ShoppingListItemNoteTransfer
    {
        if (empty($shoppingListItemNoteTransfer->getNote())) {
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
}
