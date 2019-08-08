<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingList\PermissionUpdater;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToCustomerClientInterface;

class PermissionUpdater implements PermissionUpdaterInterface
{
    /**
     * @var \Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToCustomerClientInterface $customerClient
     */
    public function __construct(ShoppingListToCustomerClientInterface $customerClient)
    {
        $this->customerClient = $customerClient;
    }

    /**
     * @return void
     */
    public function updateCompanyUserPermissions(): void
    {
        $customerTransfer = $this->customerClient->getCustomer();
        $newCustomerTransfer = (new CustomerTransfer())
            ->setIdCustomer($customerTransfer->getIdCustomer())
            ->setCompanyUserTransfer($customerTransfer->getCompanyUserTransfer());

        $updatedCustomerTransfer = $this->customerClient->getCustomerByEmail($newCustomerTransfer);
        $customerTransfer->setPermissions($updatedCustomerTransfer->getPermissions());
        $this->customerClient->setCustomer($customerTransfer);
    }
}
