<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListStorage\Storage;

use Generated\Shared\Transfer\ShoppingListCustomerStorageTransfer;

interface ShoppingListCustomerStorageInterface
{
    /**
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\ShoppingListCustomerStorageTransfer|null
     */
    public function getShoppingListCustomerStorageByCustomerReference(string $customerReference): ?ShoppingListCustomerStorageTransfer;
}
