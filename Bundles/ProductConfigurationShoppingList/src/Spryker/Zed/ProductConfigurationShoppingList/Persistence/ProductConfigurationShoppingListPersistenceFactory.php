<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationShoppingList\Persistence;

use Orm\Zed\ShoppingList\Persistence\SpyShoppingListItemQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductConfigurationShoppingList\ProductConfigurationShoppingListDependencyProvider;

/**
 * @method \Spryker\Zed\ProductConfigurationShoppingList\ProductConfigurationShoppingListConfig getConfig()
 * @method \Spryker\Zed\ProductConfigurationShoppingList\Persistence\ProductConfigurationShoppingListEntityManagerInterface getEntityManager()
 */
class ProductConfigurationShoppingListPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ShoppingList\Persistence\SpyShoppingListItemQuery
     */
    public function getShoppingListItemPropelQuery(): SpyShoppingListItemQuery
    {
        return $this->getProvidedDependency(ProductConfigurationShoppingListDependencyProvider::PROPEL_QUERY_SHOPPING_LIST_ITEM);
    }
}
