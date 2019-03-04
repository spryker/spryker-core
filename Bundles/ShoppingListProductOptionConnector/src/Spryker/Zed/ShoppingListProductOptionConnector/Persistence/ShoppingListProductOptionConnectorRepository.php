<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOptionConnector\Persistence;

use Orm\Zed\ShoppingList\Persistence\Map\SpyShoppingListItemTableMap;
use Orm\Zed\ShoppingListProductOptionConnector\Persistence\Map\SpyShoppingListProductOptionTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ShoppingListProductOptionConnector\Persistence\ShoppingListProductOptionConnectorPersistenceFactory getFactory()
 */
class ShoppingListProductOptionConnectorRepository extends AbstractRepository implements ShoppingListProductOptionConnectorRepositoryInterface
{
    /**
     * @param int $idShoppingListItem
     *
     * @return int[]
     */
    public function getShoppingListItemProductOptionIdsByIdShoppingListItem(int $idShoppingListItem): array
    {
        return $this->getFactory()
            ->createSpyShoppingListProductOptionQuery()
            ->filterByFkShoppingListItem($idShoppingListItem)
            ->select([SpyShoppingListProductOptionTableMap::COL_FK_PRODUCT_OPTION_VALUE])
            ->find()
            ->getArrayCopy();
    }

    /**
     * @param int $idShoppingListItem
     *
     * @return string
     */
    public function getShoppingListItemProductAbstractSkuByIdShoppingListItem(int $idShoppingListItem): string
    {
        return $this->getFactory()
            ->createSpyShoppingListProductOptionQuery()
            ->filterByFkShoppingListItem($idShoppingListItem)
            ->useSpyShoppingListItemQuery()
            ->select([SpyShoppingListItemTableMap::COL_SKU])
            ->findOne();
    }
}
