<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListNote\Business\ShoppingListItemNote;

use ArrayObject;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
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

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemNoteTransfer[]|\ArrayObject
     */
    public function getShoppingListItemNoteTransfersByShoppingListItemCollection(ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer): ArrayObject
    {
        $shoppingListItemIds = $this
            ->getShoppingListItemIdsFromShoppingListItemCollection($shoppingListItemCollectionTransfer);

        return $this->shoppingListNoteRepository
            ->getShoppingListItemNoteTransfersByShoppingListItemIds($shoppingListItemIds);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return int[]
     */
    protected function getShoppingListItemIdsFromShoppingListItemCollection(ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer): array
    {
        $shoppingListItemIds = [];
        foreach ($shoppingListItemCollectionTransfer->getItems() as $shoppingListItemTransfer) {
            $shoppingListItemIds[] = $shoppingListItemTransfer->getIdShoppingListItem();
        }

        return $shoppingListItemIds;
    }
}
