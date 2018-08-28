<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListNote\Business\ShoppingListItemNote;

use Generated\Shared\Transfer\ShoppingListItemNoteTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use Spryker\Zed\ShoppingListNote\Persistence\ShoppingListNoteEntityManagerInterface;

class ShoppingListItemNoteWriter implements ShoppingListItemNoteWriterInterface
{
    use PermissionAwareTrait;

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
     * @return \Generated\Shared\Transfer\ShoppingListItemNoteTransfer|null
     */
    public function saveShoppingListItemNote(ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer): ?ShoppingListItemNoteTransfer
    {
        if (empty($shoppingListItemNoteTransfer->getNote())) {
            if ($shoppingListItemNoteTransfer->getIdShoppingListItemNote()) {
                $this->shoppingListNoteEntityManager->deleteShoppingListItemNoteById($shoppingListItemNoteTransfer);
            }

            return null;
        }

        return $this->shoppingListNoteEntityManager->saveShoppingListItemNote($shoppingListItemNoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer
     *
     * @return void
     */
    public function deleteShoppingListItemNoteById(ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer): void
    {
        if ($shoppingListItemNoteTransfer->getIdShoppingListItemNote()) {
            $this->shoppingListNoteEntityManager->deleteShoppingListItemNoteById($shoppingListItemNoteTransfer);
        }
    }
}
