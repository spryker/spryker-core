<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListStorage;

use Generated\Shared\Transfer\ShoppingListCustomerStorageTransfer;
use Generated\Shared\Transfer\ShoppingListSessionTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ShoppingListStorage\ShoppingListStorageFactory getFactory()
 */
class ShoppingListStorageClient extends AbstractClient implements ShoppingListStorageClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListSessionTransfer $shoppingListSession
     *
     * @return bool
     */
    public function isShoppingListCollectionOutdated(ShoppingListSessionTransfer $shoppingListSession): bool
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();
        if (!$customerTransfer) {
            return true;
        }
        $shoppingListCustomerStorageTransfer = $this->getShoppingListCustomerStorageByCustomerReference(
            $customerTransfer->getCustomerReference()
        );
        if (!$shoppingListCustomerStorageTransfer) {
            return true;
        }
        return $shoppingListSession->getUpdatedAt() < $shoppingListCustomerStorageTransfer->getUpdatedAt();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\ShoppingListCustomerStorageTransfer|null
     */
    public function getShoppingListCustomerStorageByCustomerReference(string $customerReference): ?ShoppingListCustomerStorageTransfer
    {
        $shoppingListStorage = $this->getFactory()->createShoppingListCustomerStorage();
        $shoppingListCustomerStorage = $shoppingListStorage->getShoppingListCustomerStorageByCustomerReference($customerReference);

        return $shoppingListCustomerStorage;
    }
}
