<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage\Persistence;

use Generated\Shared\Transfer\ShoppingListCustomerStorageTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ShoppingListStorage\Persistence\ShoppingListStoragePersistenceFactory getFactory()
 */
class ShoppingListStorageEntityManager extends AbstractEntityManager implements ShoppingListStorageEntityManagerInterface
{
    /**
     * @param string $customerReference
     *
     * @return void
     */
    public function saveShoppingListCustomerStorage(string $customerReference): void
    {
        $shoppingListCustomerStorage = $this->getFactory()
            ->createShoppingListCustomerStorageQuery()
            ->filterByCustomerReference($customerReference)
            ->findOneOrCreate();

        $shoppingListCustomerStorageTransfer = new ShoppingListCustomerStorageTransfer();
        $shoppingListCustomerStorageTransfer->setUpdatedAt(time());
        $shoppingListCustomerStorage->setData($shoppingListCustomerStorageTransfer->toArray());
        $shoppingListCustomerStorage->save();
    }
}
