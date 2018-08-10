<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListStorage\Storage;

interface ShoppingListCustomerStorageInterface
{
    /**
     * @param string $customerReference
     *
     * @return mixed
     */
    public function getShoppingListCustomerStorageByCustomerReference(string $customerReference);
}
