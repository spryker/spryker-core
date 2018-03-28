<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\Order\Mapper;

use Generated\Shared\Transfer\SpySalesOrderEntityTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;

class OrderMapper implements OrderMapperInterface
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $spySalesOrder
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderEntityTransfer
     */
    public function mapOrderToEntityTransfer(SpySalesOrder $spySalesOrder): SpySalesOrderEntityTransfer
    {
        $salesOrderEntityTransfer = new SpySalesOrderEntityTransfer();
        $salesOrderEntityTransfer->fromArray($spySalesOrder->toArray(), true);

        return $salesOrderEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpySalesOrderEntityTransfer $salesOrderEntityTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function mapEntityTransferToOrder(SpySalesOrderEntityTransfer $salesOrderEntityTransfer): SpySalesOrder
    {
        $salesOrder = new SpySalesOrder();
        $salesOrder->fromArray($salesOrderEntityTransfer->toArray());

        return $salesOrder;
    }
}
