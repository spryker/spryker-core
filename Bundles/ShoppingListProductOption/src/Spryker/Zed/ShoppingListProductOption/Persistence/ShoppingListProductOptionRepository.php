<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOption\Persistence;

use Orm\Zed\ShoppingListProductOption\Persistence\Map\SpyShoppingListProductOptionTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ShoppingListProductOption\Persistence\ShoppingListProductOptionPersistenceFactory getFactory()
 */
class ShoppingListProductOptionRepository extends AbstractRepository implements ShoppingListProductOptionRepositoryInterface
{
    /**
     * @param int $idShoppingListItem
     *
     * @return int[]
     */
    public function findShoppingListItemProductOptionIdsByFkShoppingListItem(int $idShoppingListItem): array
    {
        return $this->getFactory()
            ->createSpyShoppingListProductOptionQuery()
            ->filterByFkShoppingListItem($idShoppingListItem)
            ->select([SpyShoppingListProductOptionTableMap::COL_FK_PRODUCT_OPTION_VALUE])
            ->find();
    }
}
