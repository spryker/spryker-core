<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOptionConnector\Persistence;

use ArrayObject;
use Orm\Zed\ShoppingListProductOptionConnector\Persistence\SpyShoppingListProductOption;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ShoppingListProductOptionConnector\Persistence\ShoppingListProductOptionConnectorPersistenceFactory getFactory()
 */
class ShoppingListProductOptionConnectorEntityManager extends AbstractEntityManager implements ShoppingListProductOptionConnectorEntityManagerInterface
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
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ShoppingListItemTransfer> $shoppingListItemTransfers
     *
     * @return void
     */
    public function saveShoppingListItemProductOptionInBulk(ArrayObject $shoppingListItemTransfers): void
    {
        $shoppingListItemObjectCollection = new ObjectCollection();
        $shoppingListItemObjectCollection->setModel(SpyShoppingListProductOption::class);

        foreach ($shoppingListItemTransfers as $shoppingListItemTransfer) {
            foreach ($shoppingListItemTransfer->getProductOptions() as $productOptionTransfer) {
                $shoppingListProductOptionEntity = $this->getFactory()
                    ->createSpyShoppingListProductOption()
                    ->setFkShoppingListItem($shoppingListItemTransfer->getIdShoppingListItem())
                    ->setFkProductOptionValue($productOptionTransfer->getIdProductOptionValue());

                $shoppingListItemObjectCollection->append($shoppingListProductOptionEntity);
            }
        }

        $shoppingListItemObjectCollection->save();
    }

    /**
     * @param int $idShoppingListItem
     *
     * @return void
     */
    public function removeShoppingListItemProductOptions(int $idShoppingListItem): void
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection $shoppingListProductOptionEntities */
        $shoppingListProductOptionEntities = $this->getFactory()
            ->createSpyShoppingListProductOptionQuery()
            ->filterByFkShoppingListItem($idShoppingListItem)
            ->find();

        $this->deleteEntitiesAndTriggerEvents($shoppingListProductOptionEntities);
    }

    /**
     * @param array<int> $shoppingListItemIds
     *
     * @return void
     */
    public function removeShoppingListItemProductOptionsByShoppingListItemIds(array $shoppingListItemIds): void
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection $shoppingListProductOptionEntities */
        $shoppingListProductOptionEntities = $this->getFactory()
            ->createSpyShoppingListProductOptionQuery()
            ->filterByFkShoppingListItem_In($shoppingListItemIds)
            ->find();

        $this->deleteEntitiesAndTriggerEvents($shoppingListProductOptionEntities);
    }

    /**
     * @param array<int> $productOptionValueIds
     *
     * @return void
     */
    public function removeShoppingListItemProductOptionsByProductOptionValueIds(array $productOptionValueIds): void
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection $shoppingListProductOptionEntities */
        $shoppingListProductOptionEntities = $this->getFactory()
            ->createSpyShoppingListProductOptionQuery()
            ->filterByFkProductOptionValue_In($productOptionValueIds)
            ->find();

        $this->deleteEntitiesAndTriggerEvents($shoppingListProductOptionEntities);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ShoppingListProductOptionConnector\Persistence\SpyShoppingListProductOption> $shoppingListProductOptionEntities
     *
     * @return void
     */
    protected function deleteEntitiesAndTriggerEvents(ObjectCollection $shoppingListProductOptionEntities): void
    {
        $shoppingListProductOptionEntities->delete();
    }
}
