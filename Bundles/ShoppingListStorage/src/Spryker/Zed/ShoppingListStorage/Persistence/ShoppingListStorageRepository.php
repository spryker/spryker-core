<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage\Persistence;

use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\ShoppingList\Persistence\Map\SpyShoppingListTableMap;
use Orm\Zed\ShoppingListStorage\Persistence\SpyShoppingListCustomerStorage;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ShoppingListStorage\Persistence\ShoppingListStoragePersistenceFactory getFactory()
 */
class ShoppingListStorageRepository extends AbstractRepository implements ShoppingListStorageRepositoryInterface
{
    /**
     * @param int[] $shippingListIds
     *
     * @return string[]
     */
    public function getCustomerReferencesByShippingListIds(array $shippingListIds): array
    {
        return $this->getFactory()
            ->createShippingListPropelQuery()
            ->filterByIdShoppingList_In($shippingListIds)
            ->select([SpyShoppingListTableMap::COL_CUSTOMER_REFERENCE])
            ->find()
            ->toArray();
    }

    /**
     * @param int[] $companyBusinessUnitIds
     *
     * @return string[]
     */
    public function getCustomerReferencesByCompanyBusinessUnitIds(array $companyBusinessUnitIds): array
    {
        return $this->getFactory()
            ->createCompanyUserPropelQuery()
            ->joinWithCustomer()
            ->filterByFkCompanyBusinessUnit_In($companyBusinessUnitIds)
            ->select(SpyCustomerTableMap::COL_CUSTOMER_REFERENCE)
            ->find()
            ->toArray();
    }

    /**
     * @param int[] $companyUserIds
     *
     * @return string[]
     */
    public function getCustomerReferencesByCompanyUserIds(array $companyUserIds): array
    {
        return $this->getFactory()
            ->createCompanyUserPropelQuery()
            ->joinWithCustomer()
            ->filterByIdCompanyUser_In($companyUserIds)
            ->select(SpyCustomerTableMap::COL_CUSTOMER_REFERENCE)
            ->find()
            ->toArray();
    }

    /**
     * @inheritDoc
     */
    public function findShoppingListCustomerStorageEntitiesByCustomerReference(string $customerReference): SpyShoppingListCustomerStorage
    {
        return $this->getFactory()->createShoppingListCustomerStorageQuery()
            ->filterByCustomerReference($customerReference)
            ->findOneOrCreate();
    }
}
