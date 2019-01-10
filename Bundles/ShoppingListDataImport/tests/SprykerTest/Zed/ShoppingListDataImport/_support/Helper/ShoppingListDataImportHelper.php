<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ShoppingListDataImport\Helper;

use Codeception\Module;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyBusinessUnitQuery;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyUserQuery;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListItemQuery;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListQuery;

class ShoppingListDataImportHelper extends Module
{
    /**
     * @return void
     */
    public function assertShoppingListDatabaseTablesContainsData(): void
    {
        $shoppingListQuery = $this->getShoppingListQuery();
        $this->assertTrue($shoppingListQuery->count() > 0, 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return void
     */
    public function ensureShoppingListItemDatabaseTableIsEmpty(): void
    {
        $this->getShoppingListItemQuery()->deleteAll();
    }

    /**
     * @return void
     */
    public function assertShoppingListItemDatabaseTablesContainsData(): void
    {
        $shoppingListItemQuery = $this->getShoppingListItemQuery();
        $this->assertTrue($shoppingListItemQuery->count() > 0, 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return void
     */
    public function ensureShoppingListCompanyUserDatabaseTableIsEmpty(): void
    {
        $this->getShoppingListCompanyUserQuery()->deleteAll();
    }

    /**
     * @return void
     */
    public function assertShoppingListCompanyUserDatabaseTablesContainsData(): void
    {
        $shoppingListCompanyUserQuery = $this->getShoppingListCompanyUserQuery();
        $this->assertTrue($shoppingListCompanyUserQuery->count() > 0, 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return void
     */
    public function ensureShoppingListCompanyBusinessUnitDatabaseTableIsEmpty(): void
    {
        $this->getShoppingListCompanyBusinessUnitQuery()->deleteAll();
    }

    /**
     * @return void
     */
    public function assertShoppingListCompanyBusinessUnitDatabaseTablesContainsData(): void
    {
        $shoppingListQuery = $this->getShoppingListQuery();
        $this->assertTrue($shoppingListQuery->count() > 0, 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return \Orm\Zed\ShoppingList\Persistence\SpyShoppingListQuery
     */
    protected function getShoppingListQuery(): SpyShoppingListQuery
    {
        return SpyShoppingListQuery::create();
    }

    /**
     * @return \Orm\Zed\ShoppingList\Persistence\SpyShoppingListItemQuery
     */
    protected function getShoppingListItemQuery(): SpyShoppingListItemQuery
    {
        return SpyShoppingListItemQuery::create();
    }

    /**
     * @return \Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyUserQuery
     */
    protected function getShoppingListCompanyUserQuery(): SpyShoppingListCompanyUserQuery
    {
        return SpyShoppingListCompanyUserQuery::create();
    }

    /**
     * @return \Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyBusinessUnitQuery
     */
    protected function getShoppingListCompanyBusinessUnitQuery(): SpyShoppingListCompanyBusinessUnitQuery
    {
        return SpyShoppingListCompanyBusinessUnitQuery::create();
    }
}
