<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\SpyShoppingListItemEntityTransfer;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListItem;

class ShoppingListItemMapper implements ShoppingListItemMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListItemEntityTransfer[] $itemEntityTransferCollection
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function mapItemCollectionTransfer(array $itemEntityTransferCollection): ShoppingListItemCollectionTransfer
    {
        $shoppingListItemCollectionTransfer = new ShoppingListItemCollectionTransfer();
        foreach ($itemEntityTransferCollection as $itemEntityTransfer) {
            $shoppingListItemTransfer = $this->mapItemTransfer($itemEntityTransfer, new ShoppingListItemTransfer());
            $shoppingListItemCollectionTransfer->addItem($shoppingListItemTransfer);
        }

        return $shoppingListItemCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListItemEntityTransfer $itemEntityTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function mapItemTransfer(
        SpyShoppingListItemEntityTransfer $itemEntityTransfer,
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ShoppingListItemTransfer {
        return $shoppingListItemTransfer->fromArray($itemEntityTransfer->modifiedToArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Orm\Zed\ShoppingList\Persistence\SpyShoppingListItem $shoppingListItem
     *
     * @return \Orm\Zed\ShoppingList\Persistence\SpyShoppingListItem
     */
    public function mapTransferToEntity(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        SpyShoppingListItem $shoppingListItem
    ): SpyShoppingListItem {
        $shoppingListItem->fromArray($shoppingListItemTransfer->modifiedToArray());

        return $shoppingListItem;
    }
}
