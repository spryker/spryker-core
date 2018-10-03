<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage\Persistence;

use Orm\Zed\ShoppingListStorage\Persistence\SpyShoppingListCustomerStorage;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ShoppingListStorage\Persistence\ShoppingListStoragePersistenceFactory getFactory()
 */
class ShoppingListStorageEntityManager extends AbstractEntityManager implements ShoppingListStorageEntityManagerInterface
{
    /**
     * @param \Orm\Zed\ShoppingListStorage\Persistence\SpyShoppingListCustomerStorage $shoppingListCustomerStorageEntity
     *
     * @return void
     */
    public function saveShoppingListCustomerStorage(SpyShoppingListCustomerStorage $shoppingListCustomerStorageEntity): void
    {
        $shoppingListCustomerStorageEntity->save();
    }
}
