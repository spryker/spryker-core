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
use Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Generated\Shared\Transfer\SpyShoppingListCompanyBusinessUnitEntityTransfer;
use Generated\Shared\Transfer\SpyShoppingListCompanyUserEntityTransfer;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\ShoppingList\Persistence\Map\SpyShoppingListCompanyBusinessUnitTableMap;
use Orm\Zed\ShoppingList\Persistence\Map\SpyShoppingListCompanyUserTableMap;
use Orm\Zed\ShoppingList\Persistence\Map\SpyShoppingListTableMap;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingList;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListPermissionGroup;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ShoppingList\Persistence\ShoppingListPersistenceFactory getFactory()
 */
class ShoppingListRepository extends AbstractRepository implements ShoppingListRepositoryInterface
{
    protected const FIELD_FIRST_NAME = 'first_name';
    protected const FIELD_LAST_NAME = 'last_name';

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
    public function findShoppingListById(ShoppingListTransfer $shoppingListTransfer): ShoppingListTransfer
    {
        $shoppingListEntity = $this->getFactory()->createShoppingListQuery()
            ->filterByIdShoppingList($shoppingListTransfer->getIdShoppingList())
            ->findOne();

        return $this->mapShoppingListEntityToTransfer($shoppingListEntity, $shoppingListTransfer);
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
        return $this->mapShoppingListEntitiesToCollectionTransfer($shoppingLists);
    }

    /**
     * @param int[] $shoppingListIds
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function findCustomerShoppingListsItemsByIds(array $shoppingListIds): ShoppingListItemCollectionTransfer
    {
        $shoppingListsItems = $this->getFactory()
            ->createShoppingListItemQuery()
            ->useSpyShoppingListQuery()
                ->filterByIdShoppingList_In($shoppingListIds)
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
     * @return \Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer
     */
    public function getShoppingListPermissionGroup(): ShoppingListPermissionGroupTransfer
    {
        $shoppingListPermissionGroupEntity = $this->getFactory()->createShoppingListPermissionGroupQuery()->findOne();

        return $this->mapShoppingListPermissionGroupEntityToTransfer($shoppingListPermissionGroupEntity);
    }

    /**
     * @param int $idShoppingList
     * @param int $idCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\SpyShoppingListCompanyBusinessUnitEntityTransfer|null
     */
    public function getShoppingListCompanyBusinessUnit(int $idShoppingList, int $idCompanyBusinessUnit): ?SpyShoppingListCompanyBusinessUnitEntityTransfer
    {
        $shoppingListCompanyBusinessUnitEntity = $this->getFactory()
            ->createShoppingListCompanyBusinessUnitQuery()
            ->filterByFkShoppingList($idShoppingList)
            ->filterByFkCompanyBusinessUnit($idCompanyBusinessUnit)
            ->findOne();

        if (!$shoppingListCompanyBusinessUnitEntity) {
            return null;
        }

        return (new SpyShoppingListCompanyBusinessUnitEntityTransfer())
            ->fromArray($shoppingListCompanyBusinessUnitEntity->toArray(), true);
    }

    /**
     * @param int $idShoppingList
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\SpyShoppingListCompanyUserEntityTransfer|null
     */
    public function getShoppingListCompanyUser(int $idShoppingList, int $idCompanyUser): ?SpyShoppingListCompanyUserEntityTransfer
    {
        $shoppingListCompanyUserEntity = $this->getFactory()
            ->createShoppingListCompanyUserQuery()
            ->filterByFkShoppingList($idShoppingList)
            ->filterByFkCompanyUser($idCompanyUser)
            ->findOne();

        if (!$shoppingListCompanyUserEntity) {
            return null;
        }

        return (new SpyShoppingListCompanyUserEntityTransfer())
            ->fromArray($shoppingListCompanyUserEntity->toArray(), true);
    }

    /**
     * @param int $idCompanyBusinessUnit
     *
     * @return mixed|\Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyBusinessUnit[]|\Propel\Runtime\ActiveRecord\ActiveRecordInterface[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findCompanyBusinessUnitSharedShoppingListsIds(int $idCompanyBusinessUnit): array
    {
        return $this->getFactory()
            ->createShoppingListCompanyBusinessUnitQuery()
            ->filterByFkCompanyBusinessUnit($idCompanyBusinessUnit)
            ->select(SpyShoppingListCompanyBusinessUnitTableMap::COL_FK_SHOPPING_LIST)
            ->find()
            ->toArray();
    }

    /**
     * @param int $idCompanyUser
     *
     * @return mixed|\Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyUser[]|\Propel\Runtime\ActiveRecord\ActiveRecordInterface[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findCompanyUserSharedShoppingListsIds(int $idCompanyUser): array
    {
        return $this->getFactory()
            ->createShoppingListCompanyUserQuery()
            ->filterByFkCompanyUser($idCompanyUser)
            ->select(SpyShoppingListCompanyUserTableMap::COL_FK_SHOPPING_LIST)
            ->find()
            ->toArray();
    }

    /**
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    public function findCompanyUserSharedShoppingLists(int $idCompanyUser): ShoppingListCollectionTransfer
    {
        $shoppingLists = $this->getFactory()
            ->createShoppingListQuery()
            ->addJoin(SpyShoppingListTableMap::COL_CUSTOMER_REFERENCE, SpyCustomerTableMap::COL_CUSTOMER_REFERENCE, Criteria::LEFT_JOIN)
            ->withColumn(SpyCustomerTableMap::COL_FIRST_NAME, static::FIELD_FIRST_NAME)
            ->withColumn(SpyCustomerTableMap::COL_LAST_NAME, static::FIELD_LAST_NAME)
            ->useSpyShoppingListCompanyUserQuery()
                ->filterByFkCompanyUser($idCompanyUser)
            ->endUse()
            ->find();

        return $this->mapShoppingListEntitiesToCollectionTransfer($shoppingLists);
    }

    /**
     * @param int $idCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    public function findCompanyBusinessUnitSharedShoppingLists(int $idCompanyBusinessUnit): ShoppingListCollectionTransfer
    {
        $shoppingLists = $this->getFactory()
            ->createShoppingListQuery()
            ->addJoin(SpyShoppingListTableMap::COL_CUSTOMER_REFERENCE, SpyCustomerTableMap::COL_CUSTOMER_REFERENCE, Criteria::LEFT_JOIN)
            ->withColumn(SpyCustomerTableMap::COL_FIRST_NAME, static::FIELD_FIRST_NAME)
            ->withColumn(SpyCustomerTableMap::COL_LAST_NAME, static::FIELD_LAST_NAME)
            ->useSpyShoppingListCompanyBusinessUnitQuery()
                ->filterByFkCompanyBusinessUnit($idCompanyBusinessUnit)
            ->endUse()
            ->find();

        return $this->mapShoppingListEntitiesToCollectionTransfer($shoppingLists);
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
            ->addJoin(SpyShoppingListTableMap::COL_CUSTOMER_REFERENCE, SpyCustomerTableMap::COL_CUSTOMER_REFERENCE, Criteria::LEFT_JOIN)
            ->withColumn(SpyCustomerTableMap::COL_FIRST_NAME, static::FIELD_FIRST_NAME)
            ->withColumn(SpyCustomerTableMap::COL_LAST_NAME, static::FIELD_LAST_NAME)
            ->filterByCustomerReference($customerReference)
            ->orderByIdShoppingList();
    }

    /**
     * @param \Propel\Runtime\Collection\Collection|\Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ShoppingList\Persistence\SpyShoppingListItem[] $itemEntities
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
     * @param \Generated\Shared\Transfer\ShoppingListTransfer|null $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    protected function mapShoppingListEntityToTransfer(SpyShoppingList $shoppingListEntity, ShoppingListTransfer $shoppingListTransfer = null): ShoppingListTransfer
    {
        if (!$shoppingListTransfer) {
            $shoppingListTransfer = new ShoppingListTransfer();
        }

        $shoppingListTransfer = $shoppingListTransfer->fromArray($shoppingListEntity->toArray(), true);
        if ($shoppingListEntity->hasVirtualColumn(static::FIELD_FIRST_NAME) && $shoppingListEntity->hasVirtualColumn(static::FIELD_LAST_NAME)) {
            $shoppingListTransfer->setOwner($shoppingListEntity->getVirtualColumn(static::FIELD_FIRST_NAME) . ' ' . $shoppingListEntity->getVirtualColumn(static::FIELD_LAST_NAME));
        }

        $numberOfItems = 0;
        foreach ($shoppingListEntity->getSpyShoppingListItems() as $shoppingListItem) {
            $numberOfItems += $shoppingListItem->getQuantity();
        }

        $shoppingListTransfer->setNumberOfItems($numberOfItems);
        return $shoppingListTransfer;
    }

    /**
     * @param \Orm\Zed\ShoppingList\Persistence\SpyShoppingListPermissionGroup $shoppingListPermissionGroupEntity
     *
     * @return \Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer
     */
    protected function mapShoppingListPermissionGroupEntityToTransfer(SpyShoppingListPermissionGroup $shoppingListPermissionGroupEntity): ShoppingListPermissionGroupTransfer
    {
        return (new ShoppingListPermissionGroupTransfer())->fromArray($shoppingListPermissionGroupEntity->toArray(), true);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ShoppingList\Persistence\SpyShoppingList[] $shoppingLists
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    protected function mapShoppingListEntitiesToCollectionTransfer(ObjectCollection $shoppingLists): ShoppingListCollectionTransfer
    {
        $shoppingListCollectionTransfer = new ShoppingListCollectionTransfer();

        foreach ($shoppingLists as $shoppingList) {
            $shoppingListCollectionTransfer->addShoppingList($this->mapShoppingListEntityToTransfer($shoppingList));
        }

        return $shoppingListCollectionTransfer;
    }
}
