<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage\Persistence;

interface ShoppingListStorageEntityManagerInterface
{
    /**
     * @param string $customerReference
     *
     * @return void
     */
    public function saveShoppingListCustomerStorage(string $customerReference): void;
}
