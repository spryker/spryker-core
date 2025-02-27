<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCardBalance\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\GiftCardBalance\Persistence\GiftCardBalancePersistenceFactory getFactory()
 */
class GiftCardBalanceEntityManager extends AbstractEntityManager implements GiftCardBalanceEntityManagerInterface
{
    /**
     * @param list<int> $salesOrderIds
     *
     * @return void
     */
    public function deleteGiftCardBalanceLogsBySalesOrderIds(array $salesOrderIds): void
    {
        $this->getFactory()
            ->createGiftCardBalanceLogQuery()
            ->filterByFkSalesOrder_In($salesOrderIds)
            ->delete();
    }
}
