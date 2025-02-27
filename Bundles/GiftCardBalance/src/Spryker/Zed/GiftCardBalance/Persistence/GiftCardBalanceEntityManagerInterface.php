<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCardBalance\Persistence;

interface GiftCardBalanceEntityManagerInterface
{
    /**
     * @param list<int> $salesOrderIds
     *
     * @return void
     */
    public function deleteGiftCardBalanceLogsBySalesOrderIds(array $salesOrderIds): void;
}
