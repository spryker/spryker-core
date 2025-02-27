<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Nopayment\Persistence;

interface NopaymentEntityManagerInterface
{
    /**
     * @param list<int> $salesOrderItemIds
     *
     * @return void
     */
    public function deleteNopaymentPaidEntitiesBySalesOrderItemIds(array $salesOrderItemIds): void;
}
