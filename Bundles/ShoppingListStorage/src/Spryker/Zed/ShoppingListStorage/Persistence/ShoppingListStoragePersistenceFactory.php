<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage\Persistence;

use Orm\Zed\ShoppingList\Persistence\SpyShoppingListQuery;
use Orm\Zed\ShoppingListStorage\Persistence\SpyShoppingListCustomerStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ShoppingListStorage\ShoppingListStorageDependencyProvider;

class ShoppingListStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ShoppingList\Persistence\SpyShoppingListQuery
     */
    public function createShippingListPropelQuery(): SpyShoppingListQuery
    {
        return $this->getProvidedDependency(ShoppingListStorageDependencyProvider::PROPEL_QUERY_SHOPPING_LIST);
    }

    /**
     * @return \Orm\Zed\ShoppingListStorage\Persistence\SpyShoppingListCustomerStorageQuery
     */
    public function createShoppingListCustomerStorageQuery()
    {
        return SpyShoppingListCustomerStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    public function createCompanyUserPropelQuery()
    {
        return $this->getProvidedDependency(ShoppingListStorageDependencyProvider::PROPEL_QUERY_COMPANY_USER);
    }
}
