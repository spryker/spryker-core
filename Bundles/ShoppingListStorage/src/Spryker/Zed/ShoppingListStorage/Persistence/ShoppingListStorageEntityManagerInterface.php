<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage\Persistence;

use Orm\Zed\ShoppingListStorage\Persistence\SpyShoppingListCustomerStorage;

interface ShoppingListStorageEntityManagerInterface
{
    /**
     * @param \Orm\Zed\ShoppingListStorage\Persistence\SpyShoppingListCustomerStorage $shoppingListCustomerStorageEntity
     *
     * @return void
     */
    public function saveShoppingListCustomerStorage(SpyShoppingListCustomerStorage $shoppingListCustomerStorageEntity): void;
}
