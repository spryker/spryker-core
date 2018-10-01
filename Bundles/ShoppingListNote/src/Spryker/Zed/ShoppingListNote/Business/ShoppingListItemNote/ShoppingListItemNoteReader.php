<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListNote\Business\ShoppingListItemNote;

use Generated\Shared\Transfer\ShoppingListItemNoteTransfer;
use Spryker\Zed\ShoppingListNote\Persistence\ShoppingListNoteRepositoryInterface;

class ShoppingListItemNoteReader implements ShoppingListItemNoteReaderInterface
{
    /**
     * @var \Spryker\Zed\ShoppingListNote\Persistence\ShoppingListNoteRepositoryInterface
     */
    protected $shoppingListNoteRepository;

    /**
     * @param \Spryker\Zed\ShoppingListNote\Persistence\ShoppingListNoteRepositoryInterface $shoppingListNoteRepository
     */
    public function __construct(ShoppingListNoteRepositoryInterface $shoppingListNoteRepository)
    {
        $this->shoppingListNoteRepository = $shoppingListNoteRepository;
    }

    /**
     * @param int $idShoppingListItem
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemNoteTransfer
     */
    public function getShoppingListItemNoteByIdShoppingListItem(int $idShoppingListItem): ShoppingListItemNoteTransfer
    {
        $shoppingListItemNoteTransfer = $this->shoppingListNoteRepository
            ->findShoppingListItemNoteByFkShoppingListItem($idShoppingListItem);

        if (!$shoppingListItemNoteTransfer) {
            $shoppingListItemNoteTransfer = new ShoppingListItemNoteTransfer();
        }

        return $shoppingListItemNoteTransfer;
    }
}
