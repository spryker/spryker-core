<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage\Business\Model;

interface ShoppingListCustomerStorageWriterInterface
{
    /**
     * @param array $customerReferences
     *
     * @return void
     */
    public function publish(array $customerReferences): void;
}
