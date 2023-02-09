<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseAllocation\Business;

use Generated\Shared\Transfer\OrderTransfer;

interface WarehouseAllocationFacadeInterface
{
    /**
     * Specification:
     * - Executes {@link \Spryker\Zed\WarehouseAllocationExtension\Dependency\Plugin\SalesOrderWarehouseAllocationPluginInterface} plugin stack.
     * - Creates warehouse allocation for order items.
     * - For `OrderTransfer.items` without `ItemTransfer.warehouse.id` specified, warehouse allocation is not created.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function allocateWarehouses(OrderTransfer $orderTransfer): OrderTransfer;

    /**
     * Specification:
     * - Requires `ItemTransfer.uuid` transfer property to be set.
     * - Expands order items with warehouse.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return list<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expandOrderItemsWithWarehouse(array $itemTransfers): array;
}
