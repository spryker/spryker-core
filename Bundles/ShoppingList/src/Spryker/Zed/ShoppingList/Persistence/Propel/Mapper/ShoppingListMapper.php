<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Generated\Shared\Transfer\SpyShoppingListEntityTransfer;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingList;

class ShoppingListMapper implements ShoppingListMapperInterface
{
    public const FIELD_FIRST_NAME = 'first_name';
    public const FIELD_LAST_NAME = 'last_name';
    public const FIELD_CREATED_AT = 'created_at';

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListEntityTransfer $shoppingListEntityTransfer
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function mapShoppingListTransfer(
        SpyShoppingListEntityTransfer $shoppingListEntityTransfer,
        ShoppingListTransfer $shoppingListTransfer
    ): ShoppingListTransfer {
        $shoppingListTransfer = $shoppingListTransfer->fromArray($shoppingListEntityTransfer->modifiedToArray(), true);

        $virtualPropertiesCollection = $shoppingListEntityTransfer->virtualProperties();
        if (isset($virtualPropertiesCollection[static::FIELD_FIRST_NAME]) || isset($virtualPropertiesCollection[static::FIELD_LAST_NAME])) {
            $shoppingListTransfer->setOwner(
                $virtualPropertiesCollection[static::FIELD_FIRST_NAME] . ' ' . $virtualPropertiesCollection[static::FIELD_LAST_NAME]
            );
        }
        if (isset($virtualPropertiesCollection[static::FIELD_CREATED_AT])) {
            $shoppingListTransfer->setCreatedAt($virtualPropertiesCollection[static::FIELD_CREATED_AT]);
        }

        $this->addItemsCount($shoppingListEntityTransfer, $shoppingListTransfer);

        return $shoppingListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListEntityTransfer[] $shoppingListEntityTransferCollection
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    public function mapCollectionTransfer(array $shoppingListEntityTransferCollection): ShoppingListCollectionTransfer
    {
        $shoppingListItemCollectionTransfer = new ShoppingListCollectionTransfer();
        foreach ($shoppingListEntityTransferCollection as $itemEntityTransfer) {
            $shoppingListItemTransfer = $this->mapShoppingListTransfer($itemEntityTransfer, new ShoppingListTransfer());
            $shoppingListItemCollectionTransfer->addShoppingList($shoppingListItemTransfer);
        }

        return $shoppingListItemCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     * @param \Orm\Zed\ShoppingList\Persistence\SpyShoppingList $shoppingListEntity
     *
     * @return \Orm\Zed\ShoppingList\Persistence\SpyShoppingList
     */
    public function mapTransferToEntity(ShoppingListTransfer $shoppingListTransfer, SpyShoppingList $shoppingListEntity): SpyShoppingList
    {
        $shoppingListEntity->fromArray($shoppingListTransfer->modifiedToArray());

        return $shoppingListEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListEntityTransfer $shoppingListEntityTransfer
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return void
     */
    protected function addItemsCount(
        SpyShoppingListEntityTransfer $shoppingListEntityTransfer,
        ShoppingListTransfer $shoppingListTransfer
    ): void {
        $numberOfItems = [];
        foreach ($shoppingListEntityTransfer->getSpyShoppingListItems() as $shoppingListItem) {
            $numberOfItems[$shoppingListItem->getSku()] = 1;
        }

        $shoppingListTransfer->setNumberOfItems(array_sum($numberOfItems));
    }
}
