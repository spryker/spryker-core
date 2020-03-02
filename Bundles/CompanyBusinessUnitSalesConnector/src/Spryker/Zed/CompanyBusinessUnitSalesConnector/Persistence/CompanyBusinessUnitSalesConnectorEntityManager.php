<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitSalesConnector\Persistence;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitSalesConnector\Persistence\CompanyBusinessUnitSalesConnectorPersistenceFactory getFactory()
 */
class CompanyBusinessUnitSalesConnectorEntityManager extends AbstractEntityManager implements CompanyBusinessUnitSalesConnectorEntityManagerInterface
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
