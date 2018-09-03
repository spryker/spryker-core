<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage\Persistence;

use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\ShoppingList\Persistence\Map\SpyShoppingListTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
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
    public function getCustomerReferencesByShoppingListIds(array $shoppingListIds): array
    {
        $customerReferencesArray = $this->getFactory()
            ->getShoppingListPropelQuery()

            ->distinct()
            ->useSpyShoppingListCompanyUserQuery()
                ->useSpyCompanyUserQuery(null, Criteria::LEFT_JOIN)
                    ->joinCustomer('companyUserCustomer', Criteria::LEFT_JOIN)
                ->endUse()
                ->withColumn('companyUserCustomer.customer_reference', 'companyUserReferences')
            ->endUse()
            ->useSpyShoppingListCompanyBusinessUnitQuery()
                ->useSpyCompanyBusinessUnitQuery(null, Criteria::LEFT_JOIN)
                    ->useCompanyUserQuery('companyBusinessUnitUser', Criteria::LEFT_JOIN)
                        ->joinCustomer('companyBusinessUnitCustomer', Criteria::LEFT_JOIN)
                    ->endUse()
                ->endUse()
                ->withColumn('companyBusinessUnitCustomer.customer_reference', 'companyBusinessUnitReferences')
            ->endUse()
            ->filterByIdShoppingList_In($shoppingListIds)
            ->select([SpyShoppingListTableMap::COL_CUSTOMER_REFERENCE])
            ->find()
            ->toArray();

        $result = [];
        foreach ($customerReferencesArray as $item) {
            $result = \array_merge($result, array_filter(\array_values($item)));
        };

        return \array_unique($result);
    }

    /**
     * @module Customer
     *
     * @param int[] $companyBusinessUnitIds
     *
     * @return string[]
     */
    public function getCustomerReferencesByCompanyBusinessUnitIds(array $companyBusinessUnitIds): array
    {
        return $this->getFactory()
            ->getShoppingListPropelQuery()
            ->useSpyShoppingListCompanyBusinessUnitQuery()
                ->useSpyCompanyBusinessUnitQuery()
                    ->useCompanyUserQuery()
                        ->joinCustomer()
                    ->endUse()
                ->endUse()
                ->filterByFkCompanyBusinessUnit_In($companyBusinessUnitIds)
            ->endUse()
            ->select(SpyCustomerTableMap::COL_CUSTOMER_REFERENCE)
            ->find()
            ->toArray();
    }

    /**
     * @module CompanyUser
     * @module Customer
     *
     * @param int[] $companyUserIds
     *
     * @return string[]
     */
    public function getCustomerReferencesByCompanyUserIds(array $companyUserIds): array
    {
        return $this->getFactory()
            ->getShoppingListPropelQuery()
            ->useSpyShoppingListCompanyUserQuery()
                ->useSpyCompanyUserQuery()
                    ->joinWithCustomer()
                ->endUse()
                ->filterByFkCompanyUser_In($companyUserIds)
            ->endUse()

            ->select(SpyCustomerTableMap::COL_CUSTOMER_REFERENCE)
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
