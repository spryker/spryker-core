<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOption\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ShoppingListProductOption\Persistence\ShoppingListProductOptionPersistenceFactory getFactory()
 */
class ShoppingListProductOptionEntityManager extends AbstractEntityManager implements ShoppingListProductOptionEntityManagerInterface
{
    /**
     * @param int $idShoppingListItem
     * @param int $idProductOption
     *
     * @return void
     */
    public function saveShoppingListItemProductOption(int $idShoppingListItem, int $idProductOption): void
    {
        $this->getFactory()
            ->createSpyShoppingListProductOption()
            ->setFkShoppingListItem($idShoppingListItem)
            ->setFkProductOptionValue($idProductOption)
            ->save();
    }

    /**
     * @param int $idShoppingListItem
     *
     * @return void
     */
    public function removeShoppingListItemProductOptions(int $idShoppingListItem): void
    {
        $this->getFactory()
            ->createSpyShoppingListProductOptionQuery()
            ->filterByFkShoppingListItem($idShoppingListItem)
            ->delete();
    }
}
