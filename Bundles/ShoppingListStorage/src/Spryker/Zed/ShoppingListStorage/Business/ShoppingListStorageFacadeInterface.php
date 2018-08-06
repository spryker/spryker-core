<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage\Business;

interface ShoppingListStorageFacadeInterface
{
    /**
     * @api
     *
     * @param string $customer_reference
     *
     * @return void
     */
    public function publish(string $customer_reference): void;
}
