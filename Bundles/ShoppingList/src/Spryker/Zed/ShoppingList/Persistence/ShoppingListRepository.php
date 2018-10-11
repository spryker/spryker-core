<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitBlacklistTransfer;
use Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListCompanyUserCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListCompanyUserTransfer;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListOverviewRequestTransfer;
use Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer;
use Generated\Shared\Transfer\ShoppingListPermissionGroupCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\ShoppingList\Persistence\Map\SpyShoppingListCompanyBusinessUnitTableMap;
use Orm\Zed\ShoppingList\Persistence\Map\SpyShoppingListCompanyUserTableMap;
use Orm\Zed\ShoppingList\Persistence\Map\SpyShoppingListItemTableMap;
use Orm\Zed\ShoppingList\Persistence\Map\SpyShoppingListTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\ShoppingList\Persistence\Propel\Mapper\ShoppingListMapper;

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
    public function findCustomerShoppingListByName(ShoppingListTransfer $shoppingListTransfer): ?ShoppingListTransfer
    {
        $shoppingListQuery = $this->createCustomerShoppingListQuery($shoppingListTransfer->getCustomerReference())
            ->filterByName($shoppingListTransfer->getName());

        $shoppingListEntityTransferCollection = $this->buildQueryFromCriteria($shoppingListQuery)->find();

        if ($shoppingListEntityTransferCollection) {
            return $this->getFactory()
                ->createShoppingListMapper()
                ->mapShoppingListTransfer($shoppingListEntityTransferCollection[0], new ShoppingListTransfer());
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer|null
     */
    public function findCustomerShoppingListById(ShoppingListTransfer $shoppingListTransfer): ?ShoppingListTransfer
    {
        $shoppingListQuery = $this->createCustomerShoppingListQuery($shoppingListTransfer->getCustomerReference())
            ->filterByIdShoppingList($shoppingListTransfer->getIdShoppingList());

        $shoppingListEntityTransferCollection = $this->buildQueryFromCriteria($shoppingListQuery)->find();

        if ($shoppingListEntityTransferCollection) {
            return $this->getFactory()
                ->createShoppingListMapper()
                ->mapShoppingListTransfer($shoppingListEntityTransferCollection[0], $shoppingListTransfer);
        }

        return null;
    }

    /**
     * @module Customer
     *
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer|null
     */
    public function findShoppingListById(ShoppingListTransfer $shoppingListTransfer): ?ShoppingListTransfer
    {
        $shoppingListQuery = $this->getFactory()->createShoppingListQuery()
            ->leftJoinWithSpyShoppingListItem()
            ->addJoin(SpyShoppingListTableMap::COL_CUSTOMER_REFERENCE, SpyCustomerTableMap::COL_CUSTOMER_REFERENCE, Criteria::LEFT_JOIN)
            ->withColumn(SpyCustomerTableMap::COL_FIRST_NAME, ShoppingListMapper::FIELD_FIRST_NAME)
            ->withColumn(SpyCustomerTableMap::COL_LAST_NAME, ShoppingListMapper::FIELD_LAST_NAME)
            ->filterByIdShoppingList($shoppingListTransfer->getIdShoppingList());

        $shoppingListEntityTransferCollection = $this->buildQueryFromCriteria($shoppingListQuery)->find();

        if ($shoppingListEntityTransferCollection) {
            return $this->getFactory()
                ->createShoppingListMapper()
                ->mapShoppingListTransfer($shoppingListEntityTransferCollection[0], $shoppingListTransfer);
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListOverviewRequestTransfer $shoppingListOverviewRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer
     */
    public function findShoppingListPaginatedItems(ShoppingListOverviewRequestTransfer $shoppingListOverviewRequestTransfer): ShoppingListOverviewResponseTransfer
    {
        $shoppingListItemQuery = $this->getFactory()
            ->createShoppingListItemQuery()
            ->filterByFkShoppingList($shoppingListOverviewRequestTransfer->getShoppingList()->getIdShoppingList());

        $shoppingListItemEntityTransferCollection = $this->buildQueryFromCriteria(
            $shoppingListItemQuery,
            (new FilterTransfer())
                ->setOrderBy(SpyShoppingListItemTableMap::COL_ID_SHOPPING_LIST_ITEM)
                ->setOrderDirection('ASC')
        )
            ->find();

        return (new ShoppingListOverviewResponseTransfer())
            ->setItemsCollection(
                $this->getFactory()->createShoppingListItemMapper()->mapItemCollectionTransfer($shoppingListItemEntityTransferCollection)
            );
    }

    /**
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    public function findCustomerShoppingLists(string $customerReference): ShoppingListCollectionTransfer
    {
        $shoppingListsQuery = $this->createCustomerShoppingListQuery($customerReference);
        $shoppingListsEntityTransferCollection = $this->buildQueryFromCriteria($shoppingListsQuery)->find();

        return $this->getFactory()
            ->createShoppingListMapper()
            ->mapCollectionTransfer($shoppingListsEntityTransferCollection);
    }

    /**
     * @param int[] $shoppingListIds
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function findCustomerShoppingListsItemsByIds(array $shoppingListIds): ShoppingListItemCollectionTransfer
    {
        $shoppingListsItemQuery = $this->getFactory()
            ->createShoppingListItemQuery()
            ->useSpyShoppingListQuery()
            ->filterByIdShoppingList_In($shoppingListIds)
            ->endUse();
        $shoppingListsItemEntityTransferCollection = $this->buildQueryFromCriteria($shoppingListsItemQuery)->find();

        return $this->getFactory()
            ->createShoppingListItemMapper()
            ->mapItemCollectionTransfer($shoppingListsItemEntityTransferCollection);
    }

    /**
     * @param int $idShoppingList
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function findShoppingListItemsByIdShoppingList(int $idShoppingList): ShoppingListItemCollectionTransfer
    {
        $shoppingListsItemQuery = $this->getFactory()
            ->createShoppingListItemQuery()
            ->filterByFkShoppingList($idShoppingList);

        $shoppingListsItemEntityTransferCollection = $this->buildQueryFromCriteria($shoppingListsItemQuery)->find();

        return $this->getFactory()
            ->createShoppingListItemMapper()
            ->mapItemCollectionTransfer($shoppingListsItemEntityTransferCollection);
    }

    /**
     * @param array $shoppingListItemIds
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function findShoppingListItemsByIds(array $shoppingListItemIds): ShoppingListItemCollectionTransfer
    {
        $shoppingListsItemQuery = $this->getFactory()
            ->createShoppingListItemQuery()
            ->filterByIdShoppingListItem_In($shoppingListItemIds);
        $shoppingListsItemEntityTransferCollection = $this->buildQueryFromCriteria($shoppingListsItemQuery)->find();

        return $this->getFactory()
            ->createShoppingListItemMapper()
            ->mapItemCollectionTransfer($shoppingListsItemEntityTransferCollection);
    }

    /**
     * @return \Generated\Shared\Transfer\ShoppingListPermissionGroupCollectionTransfer
     */
    public function getShoppingListPermissionGroups(): ShoppingListPermissionGroupCollectionTransfer
    {
        $shoppingListPermissionGroupQuery = $this->getFactory()->createShoppingListPermissionGroupQuery();
        $permissionGroupEntityTransferCollection = $this->buildQueryFromCriteria($shoppingListPermissionGroupQuery)->find();

        return $this->getFactory()
            ->createShoppingListPermissionGroupMapper()
            ->mapShoppingListPermissionGroupEntitiesToShoppingListPermissionTransfers($permissionGroupEntityTransferCollection, new ShoppingListPermissionGroupCollectionTransfer());
    }

    /**
     * @param int $idShoppingList
     * @param int $idCompanyBusinessUnit
     *
     * @return bool
     */
    public function isShoppingListSharedWithCompanyBusinessUnit(int $idShoppingList, int $idCompanyBusinessUnit): bool
    {
        $shoppingListCompanyBusinessUnitEntityQuery = $this->getFactory()
            ->createShoppingListCompanyBusinessUnitQuery()
            ->filterByFkShoppingList($idShoppingList)
            ->filterByFkCompanyBusinessUnit($idCompanyBusinessUnit);

        return $this->buildQueryFromCriteria($shoppingListCompanyBusinessUnitEntityQuery)->exists();
    }

    /**
     * @param int $idShoppingList
     * @param int $idCompanyUser
     *
     * @return bool
     */
    public function isShoppingListSharedWithCompanyUser(int $idShoppingList, int $idCompanyUser): bool
    {
        $shoppingListCompanyUserEntityQuery = $this->getFactory()
            ->createShoppingListCompanyUserQuery()
            ->filterByFkShoppingList($idShoppingList)
            ->filterByFkCompanyUser($idCompanyUser);

        return $this->buildQueryFromCriteria($shoppingListCompanyUserEntityQuery)->exists();
    }

    /**
     * @param int $idCompanyBusinessUnit
     *
     * @return \Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyBusinessUnit[]
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
     * @module Customer
     *
     * @param int $idCompanyBusinessUnit
     * @param string $shoppingListPermissionGroupName
     *
     * @return int[]
     */
    public function getCompanyBusinessUnitSharedShoppingListIdsByPermissionGroupName(int $idCompanyBusinessUnit, string $shoppingListPermissionGroupName): array
    {
        return $this->getFactory()
            ->createShoppingListCompanyBusinessUnitQuery()
            ->filterByFkCompanyBusinessUnit($idCompanyBusinessUnit)
            ->select(SpyShoppingListCompanyBusinessUnitTableMap::COL_FK_SHOPPING_LIST)
            ->useSpyShoppingListPermissionGroupQuery()
            ->filterByName($shoppingListPermissionGroupName)
            ->endUse()
            ->find()
            ->toArray();
    }

    /**
     * @param int $idCompanyUser
     *
     * @return \Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyUser[]
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
     * @module Customer
     *
     * @param int $idCompanyUser
     * @param string $shoppingListPermissionGroupName
     *
     * @return int[]
     */
    public function getCompanyUserSharedShoppingListIdsByPermissionGroupName(int $idCompanyUser, string $shoppingListPermissionGroupName): array
    {
        return $this->getFactory()
            ->createShoppingListCompanyUserQuery()
            ->filterByFkCompanyUser($idCompanyUser)
            ->select(SpyShoppingListCompanyUserTableMap::COL_FK_SHOPPING_LIST)
            ->useSpyShoppingListPermissionGroupQuery()
            ->filterByName($shoppingListPermissionGroupName)
            ->endUse()
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
        $shoppingListsQuery = $this->getFactory()
            ->createShoppingListQuery()
            ->addJoin(SpyShoppingListTableMap::COL_CUSTOMER_REFERENCE, SpyCustomerTableMap::COL_CUSTOMER_REFERENCE, Criteria::LEFT_JOIN)
            ->withColumn(SpyCustomerTableMap::COL_FIRST_NAME, ShoppingListMapper::FIELD_FIRST_NAME)
            ->withColumn(SpyCustomerTableMap::COL_LAST_NAME, ShoppingListMapper::FIELD_LAST_NAME)
            ->useSpyShoppingListCompanyUserQuery()
            ->filterByFkCompanyUser($idCompanyUser)
            ->endUse()
            ->leftJoinWithSpyShoppingListItem();

        $shoppingListsEntityTransferCollection = $this->buildQueryFromCriteria($shoppingListsQuery)->find();

        return $this->getFactory()
            ->createShoppingListMapper()
            ->mapCollectionTransfer($shoppingListsEntityTransferCollection);
    }

    /**
     * @module Customer
     *
     * @param int $idCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    public function findCompanyBusinessUnitSharedShoppingLists(int $idCompanyBusinessUnit): ShoppingListCollectionTransfer
    {
        $shoppingListQuery = $this->getFactory()
            ->createShoppingListQuery()
            ->addJoin(SpyShoppingListTableMap::COL_CUSTOMER_REFERENCE, SpyCustomerTableMap::COL_CUSTOMER_REFERENCE, Criteria::LEFT_JOIN)
            ->withColumn(SpyCustomerTableMap::COL_FIRST_NAME, ShoppingListMapper::FIELD_FIRST_NAME)
            ->withColumn(SpyCustomerTableMap::COL_LAST_NAME, ShoppingListMapper::FIELD_LAST_NAME)
            ->useSpyShoppingListCompanyBusinessUnitQuery()
            ->filterByFkCompanyBusinessUnit($idCompanyBusinessUnit)
            ->endUse()
            ->leftJoinWithSpyShoppingListItem();

        $shoppingListsEntityTransferCollection = $this->buildQueryFromCriteria($shoppingListQuery)->find();

        return $this->getFactory()
            ->createShoppingListMapper()
            ->mapCollectionTransfer($shoppingListsEntityTransferCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitCollectionTransfer
     */
    public function getShoppingListCompanyBusinessUnitsByShoppingListId(ShoppingListTransfer $shoppingListTransfer): ShoppingListCompanyBusinessUnitCollectionTransfer
    {
        $shoppingListsCompanyBusinessUnits = $this->getFactory()
            ->createShoppingListCompanyBusinessUnitQuery()
            ->filterByFkShoppingList($shoppingListTransfer->getIdShoppingList())
            ->find();

        $shoppingListCompanyBusinessUnitCollection = new ShoppingListCompanyBusinessUnitCollectionTransfer();

        if ($shoppingListsCompanyBusinessUnits !== null) {
            return $this->getFactory()
                ->createShoppingListCompanyBusinessUnitMapper()
                ->mapCompanyBusinessUnitEntitiesToShoppingListCompanyBusinessUnitCollection($shoppingListsCompanyBusinessUnits, $shoppingListCompanyBusinessUnitCollection);
        }

        return $shoppingListCompanyBusinessUnitCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyUserCollectionTransfer
     */
    public function getShoppingListCompanyUsersByShoppingListId(ShoppingListTransfer $shoppingListTransfer): ShoppingListCompanyUserCollectionTransfer
    {
        $shoppingListsCompanyUsers = $this->getFactory()
            ->createShoppingListCompanyUserQuery()
            ->filterByFkShoppingList($shoppingListTransfer->getIdShoppingList())
            ->find();

        $shoppingListCompanyUserCollection = new ShoppingListCompanyUserCollectionTransfer();

        if ($shoppingListsCompanyUsers !== null) {
            return $this->getFactory()
                ->createShoppingListCompanyUserMapper()
                ->mapCompanyUserEntitiesToShoppingListCompanyUserCollection($shoppingListsCompanyUsers, $shoppingListCompanyUserCollection);
        }

        return $shoppingListCompanyUserCollection;
    }

    /**
     * @module Customer
     *
     * @param string $customerReference
     *
     * @return \Orm\Zed\ShoppingList\Persistence\SpyShoppingListQuery
     */
    protected function createCustomerShoppingListQuery(string $customerReference)
    {
        return $this->getFactory()
            ->createShoppingListQuery()
            ->addJoin(SpyShoppingListTableMap::COL_CUSTOMER_REFERENCE, SpyCustomerTableMap::COL_CUSTOMER_REFERENCE, Criteria::LEFT_JOIN)
            ->withColumn(SpyCustomerTableMap::COL_FIRST_NAME, ShoppingListMapper::FIELD_FIRST_NAME)
            ->withColumn(SpyCustomerTableMap::COL_LAST_NAME, ShoppingListMapper::FIELD_LAST_NAME)
            ->filterByCustomerReference($customerReference)
            ->orderByIdShoppingList()
            ->leftJoinWithSpyShoppingListItem();
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListCompanyUserTransfer $shoppingListCompanyUserTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyUserTransfer|null
     */
    public function findShoppingListCompanyUser(ShoppingListCompanyUserTransfer $shoppingListCompanyUserTransfer): ?ShoppingListCompanyUserTransfer
    {
        $shoppingListsCompanyUserEntity = $this->getFactory()
            ->createShoppingListCompanyUserQuery()
            ->filterByFkShoppingList($shoppingListCompanyUserTransfer->getIdShoppingList())
            ->filterByFkCompanyUser($shoppingListCompanyUserTransfer->getIdCompanyUser())
            ->findOne();

        if ($shoppingListsCompanyUserEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createShoppingListCompanyUserMapper()
            ->mapCompanyUserEntityToCompanyUserTransfer($shoppingListsCompanyUserEntity, $shoppingListCompanyUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitBlacklistTransfer $shoppingListCompanyBusinessUnitBlacklistTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitBlacklistTransfer|null
     */
    public function findShoppingListCompanyBusinessUnitBlackList(
        ShoppingListCompanyBusinessUnitBlacklistTransfer $shoppingListCompanyBusinessUnitBlacklistTransfer
    ): ?ShoppingListCompanyBusinessUnitBlacklistTransfer {
        $shoppingListCompanyBusinessUnitBlacklistEntity = $this->getFactory()
            ->createShoppingListCompanyBusinessUnitBlacklistPropelQuery()
            ->filterByFkCompanyUser($shoppingListCompanyBusinessUnitBlacklistTransfer->getFkCompanyUser())
            ->filterByFkShoppingListCompanyBusinessUnit($shoppingListCompanyBusinessUnitBlacklistTransfer->getFkShoppingListCompanyBusinessUnit())
            ->findOne();

        if ($shoppingListCompanyBusinessUnitBlacklistEntity === null) {
            return null;
        }

        return $shoppingListCompanyBusinessUnitBlacklistTransfer->fromArray($shoppingListCompanyBusinessUnitBlacklistEntity->toArray());
    }

    /**
     * @param int $idCompanyUser
     *
     * @return int[]
     */
    public function getBlacklistedShoppingListIdsByIdCompanyUser(int $idCompanyUser): array
    {
        return $this->getFactory()
            ->createShoppingListCompanyBusinessUnitBlacklistPropelQuery()
            ->useSpyShoppingListCompanyBusinessUnitQuery()
                ->leftJoinSpyShoppingList()
            ->endUse()
            ->select([SpyShoppingListTableMap::COL_ID_SHOPPING_LIST])
            ->findByFkCompanyUser($idCompanyUser)
            ->toArray();
    }
}
