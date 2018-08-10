<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListStorage;

use Generated\Shared\Transfer\ShoppingListSessionTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @api
 *
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
        $shoppingList = $this->getShoppingListCustomerStorageByCustomerReference($customerTransfer->getCustomerReference());

        return $shoppingListSession->getUpdatedAt() < $shoppingList->getUpdatedAt();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $customerReference
     *
     * @return mixed
     */
    public function getShoppingListCustomerStorageByCustomerReference(string $customerReference)
    {
        $locale = $this->getFactory()->getLocaleClient()->getCurrentLocale();
        $shoppingListStorage = $this->getFactory()->createShoppingListStorage($locale);
        $shoppingList = $shoppingListStorage->getShoppingListCustomerStorageByCustomerReference($customerReference);

        return $shoppingList;
    }
}
