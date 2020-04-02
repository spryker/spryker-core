<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListNote\Business\ShoppingListItemNote;

use ArrayObject;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemNoteTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;

class ShoppingListItemExpander implements ShoppingListItemExpanderInterface
{
    /**
     * @var \Spryker\Zed\ShoppingListNote\Business\ShoppingListItemNote\ShoppingListItemNoteReaderInterface
     */
    protected $shoppingLisItemNoteReader;

    /**
     * @param \Spryker\Zed\ShoppingListNote\Business\ShoppingListItemNote\ShoppingListItemNoteReaderInterface $shoppingLisItemNoteReader
     */
    public function __construct(ShoppingListItemNoteReaderInterface $shoppingLisItemNoteReader)
    {
        $this->shoppingLisItemNoteReader = $shoppingLisItemNoteReader;
    }

    /**
     * @deprecated Use `ShoppingListItemExpander::expandItemCollection()` instead.
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function expandItem(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        $shoppingListItemNoteTransfer = $this->shoppingLisItemNoteReader
            ->getShoppingListItemNoteByIdShoppingListItem($shoppingListItemTransfer->getIdShoppingListItem());

        $shoppingListItemTransfer->setShoppingListItemNote($shoppingListItemNoteTransfer);

        return $shoppingListItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function expandItemCollection(ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer): ShoppingListItemCollectionTransfer
    {
        $shoppingListItemNoteTransfers = $this->shoppingLisItemNoteReader
            ->getShoppingListItemNoteTransfersByShoppingListItemCollection($shoppingListItemCollectionTransfer);

        $indexedShoppingListItemNoteTransfers = $this
            ->indexShoppingListItemNoteTransfersByShoppingListItemIds($shoppingListItemNoteTransfers);

        $expandedShoppingListItemTransfers = new ArrayObject();
        foreach ($shoppingListItemCollectionTransfer->getItems() as $shoppingListItemTransfer) {
            $shoppingListItemTransfer->setShoppingListItemNote(
                $indexedShoppingListItemNoteTransfers[$shoppingListItemTransfer->getIdShoppingListItem()] ?? new ShoppingListItemNoteTransfer()
            );
            $expandedShoppingListItemTransfers->append($shoppingListItemTransfer);
        }

        $shoppingListItemCollectionTransfer->setItems($expandedShoppingListItemTransfers);

        return $shoppingListItemCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemNoteTransfer[]|\ArrayObject $shoppingListItemNoteTransfers
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemNoteTransfer[]
     */
    protected function indexShoppingListItemNoteTransfersByShoppingListItemIds(ArrayObject $shoppingListItemNoteTransfers): array
    {
        $indexedShoppingListItemNoteTransfers = [];
        foreach ($shoppingListItemNoteTransfers as $shoppingListItemNoteTransfer) {
            $indexedShoppingListItemNoteTransfers[$shoppingListItemNoteTransfer->getFkShoppingListItem()] = $shoppingListItemNoteTransfer;
        }

        return $indexedShoppingListItemNoteTransfers;
    }
}
