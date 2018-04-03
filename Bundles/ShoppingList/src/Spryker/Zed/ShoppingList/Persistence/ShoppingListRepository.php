<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Persistence;

use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListOverviewRequestTransfer;
use Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer;
use Generated\Shared\Transfer\ShoppingListPaginationTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingList;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ShoppingList\Persistence\ShoppingListPersistenceFactory getFactory()
 */
class ShoppingListRepository extends AbstractRepository implements ShoppingListRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer|null
     */
    public function findCustomerShoppingListWithSameName(ShoppingListTransfer $shoppingListTransfer): ?ShoppingListTransfer
    {
        $shoppingListEntity = $this->createCustomerShoppingListQuery($shoppingListTransfer->getCustomerReference())
            ->filterByName($shoppingListTransfer->getName())
            ->findOne();

        if ($shoppingListEntity) {
            return $this->mapShoppingListEntityToTransfer($shoppingListEntity);
        }

        return $shoppingListEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function findCustomerShoppingListById(ShoppingListTransfer $shoppingListTransfer): ShoppingListTransfer
    {
        $shoppingListEntity = $this->createCustomerShoppingListQuery($shoppingListTransfer->getCustomerReference())
            ->filterByIdShoppingList($shoppingListTransfer->getIdShoppingList())
            ->findOne();

        return $this->mapShoppingListEntityToTransfer($shoppingListEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function findCustomerShoppingListByName(ShoppingListTransfer $shoppingListTransfer): ShoppingListTransfer
    {
        $shoppingListEntity = $this->createCustomerShoppingListQuery($shoppingListTransfer->getCustomerReference())
            ->filterByName($shoppingListTransfer->getName())
            ->findOne();

        return $this->mapShoppingListEntityToTransfer($shoppingListEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListOverviewRequestTransfer $shoppingListOverviewRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer
     */
    public function findShoppingListPaginatedItems(ShoppingListOverviewRequestTransfer $shoppingListOverviewRequestTransfer): ShoppingListOverviewResponseTransfer
    {
        $paginationModel = $this->getFactory()
            ->createShoppingListItemQuery()
            ->filterByFkShoppingList($shoppingListOverviewRequestTransfer->getShoppingList()->getIdShoppingList())
            ->paginate($shoppingListOverviewRequestTransfer->getPage(), $shoppingListOverviewRequestTransfer->getItemsPerPage());

        $shoppingListOverviewResponseTransfer = new ShoppingListOverviewResponseTransfer();
        $shoppingListOverviewResponseTransfer->setItemsCollection($this->mapItemEntitiesToItemCollectionTransfer($paginationModel->getResults()));
        $shoppingListOverviewResponseTransfer->setPagination((new ShoppingListPaginationTransfer())->setPage($paginationModel->getPage()));

        return $shoppingListOverviewResponseTransfer;
    }

    /**
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    public function findCustomerShoppingLists(string $customerReference): ShoppingListCollectionTransfer
    {
        $shoppingLists = $this->createCustomerShoppingListQuery($customerReference)->find();
        $shoppingListCollectionTransfer = new ShoppingListCollectionTransfer();

        foreach ($shoppingLists as $shoppingList) {
            $shoppingListCollectionTransfer->addShoppingList($this->mapShoppingListEntityToTransfer($shoppingList));
        }

        return $shoppingListCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListCollectionTransfer $shoppingListCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function findCustomerShoppingListsItemsByName(ShoppingListCollectionTransfer $shoppingListCollectionTransfer): ShoppingListItemCollectionTransfer
    {
        $shoppingListsNames = [];
        $customerReferences = [];

        foreach ($shoppingListCollectionTransfer->getShoppingLists() as $shoppingList) {
            $shoppingListsNames[] = $shoppingList->getName();
            $customerReferences[] = $shoppingList->getCustomerReference();
        }

        $shoppingListsItems = $this->getFactory()
            ->createShoppingListItemQuery()
            ->useSpyShoppingListQuery()
                ->filterByCustomerReference_In($customerReferences)
                ->filterByName_In($shoppingListsNames)
            ->endUse()
            ->find();

        return $this->mapItemEntitiesToItemCollectionTransfer($shoppingListsItems);
    }

    /**
     * @param array $shoppingListItemIds
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function findShoppingListItemsByIds(array $shoppingListItemIds): ShoppingListItemCollectionTransfer
    {
        $shoppingListsItems = $this->getFactory()
            ->createShoppingListItemQuery()
            ->filterByIdShoppingListItem_In($shoppingListItemIds)
            ->find();

        return $this->mapItemEntitiesToItemCollectionTransfer($shoppingListsItems);
    }

    /**
     * @param string $customerReference
     *
     * @return $this|\Orm\Zed\ShoppingList\Persistence\SpyShoppingListQuery
     */
    protected function createCustomerShoppingListQuery(string $customerReference)
    {
        return $this->getFactory()
            ->createShoppingListQuery()
            ->filterByCustomerReference($customerReference)
            ->orderByIdShoppingList();
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ShoppingList\Persistence\SpyShoppingListItem[] $itemEntities
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    protected function mapItemEntitiesToItemCollectionTransfer(ObjectCollection $itemEntities): ShoppingListItemCollectionTransfer
    {
        $shoppingListItemCollectionTransfer = new ShoppingListItemCollectionTransfer();
        foreach ($itemEntities as $item) {
            $shoppingListItemTransfer = (new ShoppingListItemTransfer())->fromArray($item->toArray(), true);
            $shoppingListItemCollectionTransfer->addItem($shoppingListItemTransfer);
        }

        return $shoppingListItemCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\ShoppingList\Persistence\SpyShoppingList $shoppingListEntity
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    protected function mapShoppingListEntityToTransfer(SpyShoppingList $shoppingListEntity): ShoppingListTransfer
    {
        $shoppingListTransfer = (new ShoppingListTransfer())->fromArray($shoppingListEntity->toArray(), true);
        $numberOfItems = 0;
        foreach ($shoppingListEntity->getSpyShoppingListItems() as $shoppingListItem) {
            $numberOfItems += $shoppingListItem->getQuantity();
        }

        $shoppingListTransfer->setNumberOfItems($numberOfItems);
        return $shoppingListTransfer;
    }
}
