<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListNote\Business\ShoppingListItemNote;

use Generated\Shared\Transfer\ShoppingListItemNoteTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use Spryker\Zed\ShoppingListNote\Persistence\ShoppingListNoteEntityManagerInterface;
use Spryker\Zed\ShoppingListNote\Persistence\ShoppingListNoteRepositoryInterface;

class ShoppingListItemNoteWriter implements ShoppingListItemNoteWriterInterface
{
    use PermissionAwareTrait;

    /**
     * @var \Spryker\Zed\ShoppingListNote\Persistence\ShoppingListNoteEntityManagerInterface
     */
    protected $shoppingListNoteEntityManager;

    /**
     * @var \Spryker\Zed\ShoppingListNote\Persistence\ShoppingListNoteRepositoryInterface
     */
    protected $shoppingListNoteRepository;

    /**
     * @param \Spryker\Zed\ShoppingListNote\Persistence\ShoppingListNoteEntityManagerInterface $shoppingListNoteEntityManager
     * @param \Spryker\Zed\ShoppingListNote\Persistence\ShoppingListNoteRepositoryInterface $shoppingListNoteRepository
     */
    public function __construct(
        ShoppingListNoteEntityManagerInterface $shoppingListNoteEntityManager,
        ShoppingListNoteRepositoryInterface $shoppingListNoteRepository
    ) {
        $this->shoppingListNoteEntityManager = $shoppingListNoteEntityManager;
        $this->shoppingListNoteRepository = $shoppingListNoteRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemNoteTransfer|null
     */
    public function saveShoppingListItemNote(ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer): ?ShoppingListItemNoteTransfer
    {
        if (!$this->checkWritePermission($shoppingListItemNoteTransfer)) {
            return null;
        }

        if (empty($shoppingListItemNoteTransfer->getMessage())) {
            $this->deleteShoppingListItemNoteById($shoppingListItemNoteTransfer);

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
        if ($this->checkWritePermission($shoppingListItemNoteTransfer)) {
            $this->shoppingListNoteEntityManager->deleteShoppingListItemNoteById($shoppingListItemNoteTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer
     *
     * @return bool
     */
    protected function checkWritePermission(ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer): bool
    {
        $shoppingListItemNoteTransfer->requireIdShoppingList();
        $shoppingListItemNoteTransfer->requireIdCompanyUser();

        return $this->can(
            'WriteShoppingListPermissionPlugin',
            $shoppingListItemNoteTransfer->getIdCompanyUser(),
            $shoppingListItemNoteTransfer->getIdShoppingList()
        );
    }
}
