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

interface ShoppingListItemMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListItemEntityTransfer[] $itemEntityTransferCollection
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function mapItemCollectionTransfer(array $itemEntityTransferCollection): ShoppingListItemCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListItemEntityTransfer $itemEntityTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function mapItemTransfer(
        SpyShoppingListItemEntityTransfer $itemEntityTransfer,
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ShoppingListItemTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Orm\Zed\ShoppingList\Persistence\SpyShoppingListItem $shoppingListItem
     *
     * @return \Orm\Zed\ShoppingList\Persistence\SpyShoppingListItem
     */
    public function mapTransferToEntity(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        SpyShoppingListItem $shoppingListItem
    ): SpyShoppingListItem;
}
