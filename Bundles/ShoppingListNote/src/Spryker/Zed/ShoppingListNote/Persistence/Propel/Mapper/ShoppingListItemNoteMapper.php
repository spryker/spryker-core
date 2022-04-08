<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListNote\Persistence\Propel\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemNoteTransfer;
use Orm\Zed\ShoppingListNote\Persistence\SpyShoppingListItemNote;
use Propel\Runtime\Collection\ObjectCollection;

class ShoppingListItemNoteMapper implements ShoppingListItemNoteMapperInterface
{
    /**
     * @param \Orm\Zed\ShoppingListNote\Persistence\SpyShoppingListItemNote $shoppingListItemNote
     * @param \Generated\Shared\Transfer\ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemNoteTransfer
     */
    public function mapShoppingListItemNoteTransfer(
        SpyShoppingListItemNote $shoppingListItemNote,
        ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer
    ): ShoppingListItemNoteTransfer {
        $shoppingListItemNoteTransfer->fromArray($shoppingListItemNote->toArray(), true);

        return $shoppingListItemNoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer
     * @param \Orm\Zed\ShoppingListNote\Persistence\SpyShoppingListItemNote $shoppingListItemNoteEntity
     *
     * @return \Orm\Zed\ShoppingListNote\Persistence\SpyShoppingListItemNote
     */
    public function mapShoppingListItemNoteTransferToEntity(
        ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer,
        SpyShoppingListItemNote $shoppingListItemNoteEntity
    ): SpyShoppingListItemNote {
        $shoppingListItemNoteEntity->fromArray($shoppingListItemNoteTransfer->modifiedToArray());

        return $shoppingListItemNoteEntity;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ShoppingListNote\Persistence\SpyShoppingListItemNote> $shoppingListItemEntityCollection
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ShoppingListItemNoteTransfer>
     */
    public function mapShoppingListItemEntityCollectionToTransferCollection(ObjectCollection $shoppingListItemEntityCollection): ArrayObject
    {
        $shoppingListItemNoteTransfers = new ArrayObject();

        foreach ($shoppingListItemEntityCollection as $shoppingListItemNoteEntity) {
            $shoppingListItemNoteTransfer = $this
                ->mapShoppingListItemNoteTransfer($shoppingListItemNoteEntity, new ShoppingListItemNoteTransfer());

            $shoppingListItemNoteTransfers->append($shoppingListItemNoteTransfer);
        }

        return $shoppingListItemNoteTransfers;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ShoppingListNote\Persistence\SpyShoppingListItemNote> $shoppingListItemNoteEntityCollection
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function mapShoppingListItemNoteEntityCollectionToShoppingListItemCollectionTransfer(
        ObjectCollection $shoppingListItemNoteEntityCollection,
        ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
    ): ShoppingListItemCollectionTransfer {
        $shoppingListItemNoteTransfers = $this->mapShoppingListItemEntityCollectionToTransferCollection($shoppingListItemNoteEntityCollection);
        $indexedShoppingListItemNoteTransfers = $this->indexShoppingListItemNoteTransfersByFkShoppingListItem($shoppingListItemNoteTransfers);

        foreach ($shoppingListItemCollectionTransfer->getItems() as $shoppingListItemTransfer) {
            $shoppingListItemNoteTransfer = $indexedShoppingListItemNoteTransfers[$shoppingListItemTransfer->getIdShoppingListItem()] ?? null;

            if ($shoppingListItemNoteTransfer) {
                $shoppingListItemTransfer->setShoppingListItemNote($shoppingListItemNoteTransfer);
            }
        }

        return $shoppingListItemCollectionTransfer;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ShoppingListItemNoteTransfer> $shoppingListItemNoteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ShoppingListItemNoteTransfer>
     */
    protected function indexShoppingListItemNoteTransfersByFkShoppingListItem(ArrayObject $shoppingListItemNoteTransfers): array
    {
        $indexedShoppingListItemNoteTransfers = [];
        foreach ($shoppingListItemNoteTransfers as $shoppingListItemNoteTransfer) {
            $indexedShoppingListItemNoteTransfers[$shoppingListItemNoteTransfer->getFkShoppingListItem()] = $shoppingListItemNoteTransfer;
        }

        return $indexedShoppingListItemNoteTransfers;
    }
}
