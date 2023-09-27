<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseAllocation\Communication\Mapper;

use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;

interface OrderMapperInterface
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param list<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItemEntities
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function mapOrderEntityAndOrderItemEntitiesToOrderTransfer(
        SpySalesOrder $orderEntity,
        array $orderItemEntities,
        OrderTransfer $orderTransfer
    ): OrderTransfer;
}
