<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseAllocationExtension\Dependency\Plugin;

use Generated\Shared\Transfer\OrderTransfer;

/**
 * Provides extension capabilities to allocate warehouses to a sales order.
 */
interface SalesOrderWarehouseAllocationPluginInterface
{
    /**
     * Specification:
     * - Associates warehouses to a sales order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function allocateWarehouse(OrderTransfer $orderTransfer): OrderTransfer;
}
