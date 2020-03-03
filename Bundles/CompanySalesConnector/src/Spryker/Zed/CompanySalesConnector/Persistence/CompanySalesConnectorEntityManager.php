<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySalesConnector\Persistence;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\CompanySalesConnector\Persistence\CompanySalesConnectorPersistenceFactory getFactory()
 */
class CompanySalesConnectorEntityManager extends AbstractEntityManager implements CompanySalesConnectorEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function updateOrder(OrderTransfer $orderTransfer): void
    {
        $orderTransfer->requireIdSalesOrder();

        $salesOrderEntity = $this->getFactory()
            ->getSalesOrderPropelQuery()
            ->findOneByIdSalesOrder($orderTransfer->getIdSalesOrder());

        if (!$salesOrderEntity) {
            return;
        }

        $salesOrderEntity->fromArray($orderTransfer->toArray());

        $salesOrderEntity->save();
    }
}
