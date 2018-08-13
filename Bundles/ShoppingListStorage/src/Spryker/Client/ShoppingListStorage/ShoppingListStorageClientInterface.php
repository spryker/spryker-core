<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListStorage;

use Generated\Shared\Transfer\ShoppingListCustomerStorageTransfer;
use Generated\Shared\Transfer\ShoppingListSessionTransfer;

interface ShoppingListStorageClientInterface
{
    /**
     * Check if Shopping List Collection in Session is outdated.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListSessionTransfer $shoppingListSession
     *
     * @return bool
     */
    public function isShoppingListCollectionOutdated(ShoppingListSessionTransfer $shoppingListSession): bool;

    /**
     * Gets Shopping List from storage.
     *
     * @api
     *
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\ShoppingListCustomerStorageTransfer|null
     */
    public function getShoppingListCustomerStorageByCustomerReference(string $customerReference): ?ShoppingListCustomerStorageTransfer;
}
