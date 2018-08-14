<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListStorage\OutdateChecker;

use Generated\Shared\Transfer\ShoppingListCustomerStorageTransfer;
use Generated\Shared\Transfer\ShoppingListSessionTransfer;
use Spryker\Client\ShoppingListStorage\Dependency\Client\ShoppingListStorageToCustomerInterface;
use Spryker\Client\ShoppingListStorage\Storage\ShoppingListCustomerStorageInterface;

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

class ShoppingListCollectionOutdateChecker implements ShoppingListCollectionOutdateCheckerInterface
{
    /**
     * @var \Spryker\Client\ShoppingListStorage\Dependency\Client\ShoppingListStorageToCustomerInterface
     */
    protected $customerClient;

    /**
     * @var \Spryker\Client\ShoppingListStorage\Storage\ShoppingListCustomerStorageInterface
     */
    protected $shoppingListCustomerStorage;

    /**
     * @param \Spryker\Client\ShoppingListStorage\Dependency\Client\ShoppingListStorageToCustomerInterface $customerClient
     * @param \Spryker\Client\ShoppingListStorage\Storage\ShoppingListCustomerStorageInterface $shoppingListCustomerStorage
     */
    public function __construct(
        ShoppingListStorageToCustomerInterface $customerClient,
        ShoppingListCustomerStorageInterface $shoppingListCustomerStorage
    ) {
        $this->customerClient = $customerClient;
        $this->shoppingListCustomerStorage = $shoppingListCustomerStorage;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListSessionTransfer $shoppingListSession
     *
     * @return bool
     */
    public function isShoppingListCollectionOutdated(ShoppingListSessionTransfer $shoppingListSession): bool
    {
        $customerTransfer = $this->customerClient->getCustomer();
        if (!$customerTransfer) {
            return false;
        }
        $shoppingListCustomerStorageTransfer = $this->getShoppingListCustomerStorageByCustomerReference(
            $customerTransfer->getCustomerReference()
        );
        if (!$shoppingListCustomerStorageTransfer) {
            return false;
        }
        return $shoppingListSession->getUpdatedAt() < $shoppingListCustomerStorageTransfer->getUpdatedAt();
    }

    /**
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\ShoppingListCustomerStorageTransfer|null
     */
    protected function getShoppingListCustomerStorageByCustomerReference(string $customerReference): ?ShoppingListCustomerStorageTransfer
    {
        return $this->shoppingListCustomerStorage->getShoppingListCustomerStorageByCustomerReference($customerReference);
    }
}
