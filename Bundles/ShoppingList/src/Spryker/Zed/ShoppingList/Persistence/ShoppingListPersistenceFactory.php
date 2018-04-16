<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Persistence;

use Orm\Zed\Permission\Persistence\SpyPermissionQuery;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyBusinessUnitQuery;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyUserQuery;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListItemQuery;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListPermissionGroupQuery;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListPermissionGroupToPermissionQuery;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\ShoppingList\ShoppingListConfig getConfig()
 */
class ShoppingListPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ShoppingList\Persistence\SpyShoppingListQuery
     */
    public function createShoppingListQuery(): SpyShoppingListQuery
    {
        return SpyShoppingListQuery::create();
    }

    /**
     * @return \Orm\Zed\ShoppingList\Persistence\SpyShoppingListItemQuery
     */
    public function createShoppingListItemQuery(): SpyShoppingListItemQuery
    {
        return SpyShoppingListItemQuery::create();
    }

    /**
     * @return \Orm\Zed\ShoppingList\Persistence\SpyShoppingListPermissionGroupQuery
     */
    public function createShoppingListPermissionGroupQuery(): SpyShoppingListPermissionGroupQuery
    {
        return SpyShoppingListPermissionGroupQuery::create();
    }

    /**
     * @return \Orm\Zed\Permission\Persistence\SpyPermissionQuery
     */
    public function createPermissionQuery(): SpyPermissionQuery
    {
        return SpyPermissionQuery::create();
    }

    /**
     * @return \Orm\Zed\ShoppingList\Persistence\SpyShoppingListPermissionGroupToPermissionQuery
     */
    public function createShoppingListPermissionGroupToPermissionQuery(): SpyShoppingListPermissionGroupToPermissionQuery
    {
        return SpyShoppingListPermissionGroupToPermissionQuery::create();
    }

    /**
     * @return \Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyBusinessUnitQuery
     */
    public function createShoppingListCompanyBusinessUnitQuery(): SpyShoppingListCompanyBusinessUnitQuery
    {
        return SpyShoppingListCompanyBusinessUnitQuery::create();
    }

    /**
     * @return \Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyUserQuery
     */
    public function createShoppingListCompanyUserQuery(): SpyShoppingListCompanyUserQuery
    {
        return SpyShoppingListCompanyUserQuery::create();
    }
}
