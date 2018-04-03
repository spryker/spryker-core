<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Persistence;

use Generated\Shared\Transfer\SpyShoppingListEntityTransfer;
use Generated\Shared\Transfer\SpyShoppingListItemEntityTransfer;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListItem;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ShoppingList\Persistence\ShoppingListPersistenceFactory getFactory()
 */
class ShoppingListEntityManager extends AbstractEntityManager implements ShoppingListEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListEntityTransfer $shoppingListEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyShoppingListEntityTransfer|\Spryker\Shared\Kernel\Transfer\EntityTransferInterface
     */
    public function saveShoppingList(SpyShoppingListEntityTransfer $shoppingListEntityTransfer): SpyShoppingListEntityTransfer
    {
        return $this->save($shoppingListEntityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListEntityTransfer $shoppingListEntityTransfer
     *
     * @return void
     */
    public function deleteShoppingListByName(SpyShoppingListEntityTransfer $shoppingListEntityTransfer): void
    {
        $this->getFactory()
            ->createShoppingListQuery()
            ->filterByCustomerReference($shoppingListEntityTransfer->getCustomerReference())
            ->filterByName($shoppingListEntityTransfer->getName())
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListEntityTransfer $shoppingListEntityTransfer
     *
     * @return void
     */
    public function deleteShoppingListItems(SpyShoppingListEntityTransfer $shoppingListEntityTransfer): void
    {
        $shoppingListItems = $this->getFactory()
            ->createShoppingListItemQuery()
            ->useSpyShoppingListQuery()
                ->filterByName($shoppingListEntityTransfer->getName())
                ->filterByCustomerReference($shoppingListEntityTransfer->getCustomerReference())
            ->endUse()
            ->find();

        foreach ($shoppingListItems as $shoppingListItem) {
            $shoppingListItem->delete();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListItemEntityTransfer $shoppingListItemEntityTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\EntityTransferInterface|\Generated\Shared\Transfer\SpyShoppingListItemEntityTransfer
     */
    public function saveShoppingListItem(SpyShoppingListItemEntityTransfer $shoppingListItemEntityTransfer): SpyShoppingListItemEntityTransfer
    {
        return $this->save($shoppingListItemEntityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListItemEntityTransfer $shoppingListItemEntityTransfer
     *
     * @return void
     */
    public function deleteShoppingListItem(SpyShoppingListItemEntityTransfer $shoppingListItemEntityTransfer): void
    {
        (new SpyShoppingListItem())->setIdShoppingListItem($shoppingListItemEntityTransfer->getIdShoppingListItem())->delete();
    }
}
