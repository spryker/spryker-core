<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage\Persistence;

use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\ShoppingList\Persistence\Map\SpyShoppingListCompanyBusinessUnitTableMap;
use Orm\Zed\ShoppingList\Persistence\Map\SpyShoppingListCompanyUserTableMap;
use Orm\Zed\ShoppingList\Persistence\Map\SpyShoppingListTableMap;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ShoppingListStorage\Persistence\ShoppingListStoragePersistenceFactory getFactory()
 */
class ShoppingListStorageRepository extends AbstractRepository implements ShoppingListStorageRepositoryInterface
{
    /**
     * @module ShoppingList
     *
     * @param int[] $shoppingListIds
     *
     * @return string[]
     */
    public function getOwnCustomerReferencesByShoppingListIds(array $shoppingListIds): array
    {
        return $this->getFactory()
            ->getShoppingListPropelQuery()
            ->filterByIdShoppingList_In($shoppingListIds)
            ->select([SpyShoppingListTableMap::COL_CUSTOMER_REFERENCE])
            ->find()
            ->toArray();
    }

    /**
     * @module ShoppingList
     * @module CompanyUser
     * @module Customer
     *
     * @param int[] $shoppingListIds
     *
     * @return string[]
     */
    public function getSharedWithCompanyUserCustomerReferencesByShoppingListIds(array $shoppingListIds): array
    {
        return $this->getFactory()
            ->getShoppingListCompanyUserQuery()
            ->useSpyCompanyUserQuery()
                    ->joinCustomer()
            ->endUse()
            ->filterByFkShoppingList_In($shoppingListIds)
            ->select([SpyCustomerTableMap::COL_CUSTOMER_REFERENCE])
            ->find()
            ->toArray();
    }

    /**
     * @module ShoppingList
     * @module CompanyBusinessUnit
     * @module CompanyUser
     * @module Customer
     *
     * @param int[] $shoppingListIds
     *
     * @return string[]
     */
    public function getSharedWithCompanyBusinessUnitCustomerReferencesByShoppingListIds(array $shoppingListIds): array
    {
        return $this->getFactory()
            ->getShoppingListCompanyBusinessUnitQuery()
            ->useSpyCompanyBusinessUnitQuery()
                ->useCompanyUserQuery()
                    ->joinCustomer()
                ->endUse()
            ->endUse()
            ->filterByFkShoppingList_In($shoppingListIds)
            ->select([SpyCustomerTableMap::COL_CUSTOMER_REFERENCE])
            ->find()
            ->toArray();
    }

    /**
     * @module ShoppingList
     *
     * @param int[] $companyBusinessUnitIds
     *
     * @return int[]
     */
    public function getShoppingListIdsByCompanyBusinessUnitIds(array $companyBusinessUnitIds): array
    {
        return $this->getFactory()
            ->getShoppingListCompanyBusinessUnitQuery()
            ->filterByFkCompanyBusinessUnit_In($companyBusinessUnitIds)
            ->select(SpyShoppingListCompanyBusinessUnitTableMap::COL_FK_SHOPPING_LIST)
            ->find()
            ->toArray();
    }

    /**
     * @module ShoppingList
     *
     * @param int[] $companyUserIds
     *
     * @return int[]
     */
    public function getShoppingListIdsByCompanyUserIds(array $companyUserIds): array
    {
        return $this->getFactory()
            ->getShoppingListCompanyUserQuery()
            ->filterByFkCompanyUser_In($companyUserIds)
            ->select(SpyShoppingListCompanyUserTableMap::COL_FK_SHOPPING_LIST)
            ->find()
            ->toArray();
    }

    /**
     * @param array $customerReferences
     *
     * @return \Orm\Zed\ShoppingListStorage\Persistence\SpyShoppingListCustomerStorage[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findShoppingListCustomerStorageEntitiesByCustomerReferences(array $customerReferences): ObjectCollection
    {
        return $this->getFactory()
            ->createShoppingListCustomerStoragePropelQuery()
            ->filterByCustomerReference_In($customerReferences)
            ->find();
    }

    /**
     * @param string[] $customerReferences
     *
     * @return \Orm\Zed\ShoppingList\Persistence\SpyShoppingList[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findShoppingListEntitiesByCustomerReferences(array $customerReferences): ObjectCollection
    {
        return $this->getFactory()
            ->getShoppingListPropelQuery()
            ->filterByCustomerReference_In($customerReferences)
            ->find();
    }

    /**
     * @param array $shoppingListCustomerStorageIds
     *
     * @return \Orm\Zed\ShoppingListStorage\Persistence\SpyShoppingListCustomerStorage[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findShoppingListCustomerStorageEntitiesByIds(array $shoppingListCustomerStorageIds): ObjectCollection
    {
        return $this->getFactory()
            ->createShoppingListCustomerStoragePropelQuery()
            ->filterByIdShoppingListCustomerStorage_In($shoppingListCustomerStorageIds)
            ->find();
    }
}
