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
     * @param int[] $idProductOptions
     *
     * @return void
     */
    public function saveShoppingListItemProductOptions(int $idShoppingListItem, array $idProductOptions): void
    {
        foreach ($idProductOptions as $idProductOption) {
            $this->saveShoppingListItemProductOption($idShoppingListItem, $idProductOption);
        }
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

    /**
     * @param int $idShoppingListItem
     * @param int $idProductOption
     *
     * @return void
     */
    protected function saveShoppingListItemProductOption(int $idShoppingListItem, int $idProductOption): void
    {
        $shoppingListProductOption = $this->getFactory()
            ->createSpyShoppingListProductOptionQuery()
            ->filterByFkShoppingListItem($idShoppingListItem)
            ->filterByFkProductOptionValue($idProductOption)
            ->findOneOrCreate();

        $shoppingListProductOption->save();
    }
}
