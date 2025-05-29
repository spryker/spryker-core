<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerDiscountConnector\Persistence;

interface CustomerDiscountConnectorEntityManagerInterface
{
    /**
     * @param int $idCustomer
     * @param array<int> $discountIds
     *
     * @return void
     */
    public function createCustomerDiscounts(int $idCustomer, array $discountIds): void;
}
