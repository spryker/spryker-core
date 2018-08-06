<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage\Persistence;

use DateTime;
use Spryker\Shared\ShoppingListStorage\ShoppingListStorageConstants;
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

        $shoppingListCustomerStorage->setData([
            ShoppingListStorageConstants::SHOPPING_LIST_STORAGE_DATA_KEY => $now = (new DateTime())->getTimestamp(),
        ]);
        $shoppingListCustomerStorage->save();
    }
}
