<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
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
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\ShoppingList\Persistence\Propel\Mapper\ShoppingListMapperInterface;

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
                ->mapShoppingListTransfer($shoppingListEntityTransferCollection[0], $shoppingListTransfer);
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return null|\Generated\Shared\Transfer\ShoppingListTransfer
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
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return null|\Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function findShoppingListById(ShoppingListTransfer $shoppingListTransfer): ?ShoppingListTransfer
    {
        $shoppingListQuery = $this->getFactory()->createShoppingListQuery()
            ->leftJoinWithSpyShoppingListItem()
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

        $filterTransfer = new FilterTransfer();
        $filterTransfer->setLimit($shoppingListOverviewRequestTransfer->getItemsPerPage());
        $filterTransfer->setOffset($shoppingListOverviewRequestTransfer->getPage() - 1);
        $shoppingListItemEntityTransferCollection = $this->buildQueryFromCriteria($shoppingListItemQuery, $filterTransfer)->find();

        $shoppingListOverviewResponseTransfer = new ShoppingListOverviewResponseTransfer();
        $shoppingListOverviewResponseTransfer->setItemsCollection(
            $this->getFactory()->createShoppingListItemMapper()->mapItemCollectionTransfer($shoppingListItemEntityTransferCollection)
        );
        $shoppingListOverviewResponseTransfer->setPagination((new ShoppingListPaginationTransfer())->setPage($shoppingListOverviewRequestTransfer->getPage()));

        return $shoppingListOverviewResponseTransfer;
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
     * @return \Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer
     */
    public function getShoppingListPermissionGroup(): ShoppingListPermissionGroupTransfer
    {
        $shoppingListPermissionGroupQuery = $this->getFactory()->createShoppingListPermissionGroupQuery();
        $permissionGroupEntityTransfer = $this->buildQueryFromCriteria($shoppingListPermissionGroupQuery)->findOne();

        return $this->getFactory()
            ->createShoppingListPermissionGroupMapper()
            ->mapShoppingListPermissionGroupTransfer($permissionGroupEntityTransfer, new ShoppingListPermissionGroupTransfer());
    }

    /**
     * @param int $idShoppingList
     * @param int $idCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\SpyShoppingListCompanyBusinessUnitEntityTransfer|null
     */
    public function getShoppingListCompanyBusinessUnit(int $idShoppingList, int $idCompanyBusinessUnit): ?SpyShoppingListCompanyBusinessUnitEntityTransfer
    {
        $shoppingListCompanyBusinessUnitEntityQuery = $this->getFactory()
            ->createShoppingListCompanyBusinessUnitQuery()
            ->filterByFkShoppingList($idShoppingList)
            ->filterByFkCompanyBusinessUnit($idCompanyBusinessUnit);

        return $this->buildQueryFromCriteria($shoppingListCompanyBusinessUnitEntityQuery)->findOne();
    }

    /**
     * @param int $idShoppingList
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\SpyShoppingListCompanyUserEntityTransfer|null
     */
    public function getShoppingListCompanyUser(int $idShoppingList, int $idCompanyUser): ?SpyShoppingListCompanyUserEntityTransfer
    {
        $shoppingListCompanyUserEntityQuery = $this->getFactory()
            ->createShoppingListCompanyUserQuery()
            ->filterByFkShoppingList($idShoppingList)
            ->filterByFkCompanyUser($idCompanyUser);

        return $this->buildQueryFromCriteria($shoppingListCompanyUserEntityQuery)->findOne();
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
        $shoppingListsQuery = $this->getFactory()
            ->createShoppingListQuery()
            ->addJoin(SpyShoppingListTableMap::COL_CUSTOMER_REFERENCE, SpyCustomerTableMap::COL_CUSTOMER_REFERENCE, Criteria::LEFT_JOIN)
            ->withColumn(SpyCustomerTableMap::COL_FIRST_NAME, ShoppingListMapperInterface::FIELD_FIRST_NAME)
            ->withColumn(SpyCustomerTableMap::COL_LAST_NAME, ShoppingListMapperInterface::FIELD_LAST_NAME)
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
     * @param int $idCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    public function findCompanyBusinessUnitSharedShoppingLists(int $idCompanyBusinessUnit): ShoppingListCollectionTransfer
    {
        $shoppingListQuery = $this->getFactory()
            ->createShoppingListQuery()
            ->addJoin(SpyShoppingListTableMap::COL_CUSTOMER_REFERENCE, SpyCustomerTableMap::COL_CUSTOMER_REFERENCE, Criteria::LEFT_JOIN)
            ->withColumn(SpyCustomerTableMap::COL_FIRST_NAME, ShoppingListMapperInterface::FIELD_FIRST_NAME)
            ->withColumn(SpyCustomerTableMap::COL_LAST_NAME, ShoppingListMapperInterface::FIELD_LAST_NAME)
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
     * @param string $customerReference
     *
     * @return $this|\Orm\Zed\ShoppingList\Persistence\SpyShoppingListQuery
     */
    protected function createCustomerShoppingListQuery(string $customerReference)
    {
        return $this->getFactory()
            ->createShoppingListQuery()
            ->addJoin(SpyShoppingListTableMap::COL_CUSTOMER_REFERENCE, SpyCustomerTableMap::COL_CUSTOMER_REFERENCE, Criteria::LEFT_JOIN)
            ->withColumn(SpyCustomerTableMap::COL_FIRST_NAME, ShoppingListMapperInterface::FIELD_FIRST_NAME)
            ->withColumn(SpyCustomerTableMap::COL_LAST_NAME, ShoppingListMapperInterface::FIELD_LAST_NAME)
            ->filterByCustomerReference($customerReference)
            ->orderByIdShoppingList()
            ->leftJoinWithSpyShoppingListItem();
    }
}
