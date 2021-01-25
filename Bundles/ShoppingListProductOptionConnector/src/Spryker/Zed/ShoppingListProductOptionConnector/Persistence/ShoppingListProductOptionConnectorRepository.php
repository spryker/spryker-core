<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOptionConnector\Persistence;

use Generated\Shared\Transfer\ShoppingListProductOptionCollectionTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
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
     * @module Product
     * @module ProductOption
     * @module ShoppingList
     *
     * @param int[] $shoppingListItemIds
     *
     * @return \Generated\Shared\Transfer\ShoppingListProductOptionCollectionTransfer
     */
    public function getShoppingListProductOptionCollectionByShoppingListItemIds(array $shoppingListItemIds): ShoppingListProductOptionCollectionTransfer
    {
        $shoppingListProductOptionEntityCollection = $this->getFactory()
            ->createSpyShoppingListProductOptionQuery()
            ->joinSpyShoppingListItem()
            ->useSpyProductOptionValueQuery()
                ->useSpyProductOptionGroupQuery()
                    ->useSpyProductAbstractProductOptionGroupQuery()
                        ->useSpyProductAbstractQuery()
                            ->joinSpyProduct()
                        ->endUse()
                    ->endUse()
                ->endUse()
            ->endUse()
            ->filterByFkShoppingListItem_In($shoppingListItemIds)
            ->where(SpyShoppingListItemTableMap::COL_SKU . ' = ' . SpyProductTableMap::COL_SKU)
            ->find();

        return $this->getFactory()
            ->createShoppingListProductOptionMapper()
            ->mapShoppingListProductOptionEntityCollectionToShoppingListProductOptionCollectionTransfer(
                $shoppingListProductOptionEntityCollection,
                new ShoppingListProductOptionCollectionTransfer()
            );
    }
}
