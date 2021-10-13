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

interface ShoppingListItemNoteMapperInterface
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
    ): ShoppingListItemNoteTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer
     * @param \Orm\Zed\ShoppingListNote\Persistence\SpyShoppingListItemNote $shoppingListItemNoteEntity
     *
     * @return \Orm\Zed\ShoppingListNote\Persistence\SpyShoppingListItemNote
     */
    public function mapShoppingListItemNoteTransferToEntity(
        ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer,
        SpyShoppingListItemNote $shoppingListItemNoteEntity
    ): SpyShoppingListItemNote;

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ShoppingListNote\Persistence\SpyShoppingListItemNote> $shoppingListItemEntityCollection
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ShoppingListItemNoteTransfer>
     */
    public function mapShoppingListItemEntityCollectionToTransferCollection(ObjectCollection $shoppingListItemEntityCollection): ArrayObject;

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ShoppingListNote\Persistence\SpyShoppingListItemNote> $shoppingListItemNoteEntityCollection
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function mapShoppingListItemNoteEntityCollectionToShoppingListItemCollectionTransfer(
        ObjectCollection $shoppingListItemNoteEntityCollection,
        ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
    ): ShoppingListItemCollectionTransfer;
}
