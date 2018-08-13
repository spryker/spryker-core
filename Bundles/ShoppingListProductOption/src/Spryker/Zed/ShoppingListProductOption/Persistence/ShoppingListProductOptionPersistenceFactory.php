<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOption\Persistence;

use Orm\Zed\ShoppingListProductOption\Persistence\SpyShoppingListProductOptionQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\ShoppingListProductOption\ShoppingListProductOptionConfig getConfig()
 * @method \Spryker\Zed\ShoppingListProductOption\Persistence\ShoppingListProductOptionQueryContainerInterface getQueryContainer()
 */
class ShoppingListProductOptionPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ShoppingListProductOption\Persistence\SpyShoppingListProductOptionQuery
     */
    public function createSpyShoppingListProductOptionQuery(): SpyShoppingListProductOptionQuery
    {
        return SpyShoppingListProductOptionQuery::create();
    }
}
