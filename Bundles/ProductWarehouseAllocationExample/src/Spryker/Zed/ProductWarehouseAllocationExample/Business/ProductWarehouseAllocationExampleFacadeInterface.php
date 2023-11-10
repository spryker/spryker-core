<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductWarehouseAllocationExample\Business;

use Generated\Shared\Transfer\OrderTransfer;

interface ProductWarehouseAllocationExampleFacadeInterface
{
    /**
     * Specification:
     * - Requires `OrderTransfer.store` to be set.
     * - Requires `ItemTransfer.sku` for each item in `OrderTransfer` to be set.
     * - Iterates over `OrderTransfer.items`.
     * - Does nothing if `ItemTransfer.warehouse.idStock` is set.
     * - Finds the first warehouse by provided `ItemTransfer.sku`, `ItemTransfer.quantity` and `OrderTransfer.store`
     *   from `spy_stock_product` DB table with never out of stock or with the highest quantity.
     * - Sets found `StockTransfer` to `ItemTransfer.warehouse`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function allocateSalesOrderWarehouse(OrderTransfer $orderTransfer): OrderTransfer;
}
