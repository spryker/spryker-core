<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Nopayment\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Nopayment\Persistence\NopaymentPersistenceFactory getFactory()
 */
class NopaymentEntityManager extends AbstractEntityManager implements NopaymentEntityManagerInterface
{
    /**
     * @param list<int> $salesOrderItemIds
     *
     * @return void
     */
    public function deleteNopaymentPaidEntitiesBySalesOrderItemIds(array $salesOrderItemIds): void
    {
        $this->getFactory()
            ->createNopaymentPaidQuery()
            ->filterByFkSalesOrderItem_In($salesOrderItemIds)
            ->delete();
    }
}
