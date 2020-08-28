<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Model\Order;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;

interface OrderItemExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntity
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer
     */
    public function expandOrderItem(
        ItemTransfer $itemTransfer,
        SpySalesOrderItemEntityTransfer $salesOrderItemEntity
    ): SpySalesOrderItemEntityTransfer;

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandOrderItemsWithQuantitySalesUnit(array $itemTransfers): array;
}
