<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Persistence;

use Orm\Zed\ShoppingList\Persistence\SpyShoppingListItemQuery;
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
}
