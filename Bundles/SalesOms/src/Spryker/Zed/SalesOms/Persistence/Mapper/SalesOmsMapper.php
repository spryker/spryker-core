<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOms\Persistence\Mapper;

use Generated\Shared\Transfer\SalesOrderItemTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

class SalesOmsMapper
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItemEntity
     * @param \Generated\Shared\Transfer\SalesOrderItemTransfer $salesOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemTransfer
     */
    public function mapSalesOrderItemEntityToSalesOrderItemTransfer(
        SpySalesOrderItem $salesOrderItemEntity,
        SalesOrderItemTransfer $salesOrderItemTransfer
    ): SalesOrderItemTransfer {
        return (new SalesOrderItemTransfer())->fromArray($salesOrderItemEntity->toArray(), true);
    }
}
