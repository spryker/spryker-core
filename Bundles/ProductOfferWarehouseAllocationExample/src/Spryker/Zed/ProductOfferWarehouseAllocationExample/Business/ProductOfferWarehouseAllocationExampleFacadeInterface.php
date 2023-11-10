<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferWarehouseAllocationExample\Business;

use Generated\Shared\Transfer\OrderTransfer;

interface ProductOfferWarehouseAllocationExampleFacadeInterface
{
    /**
     * Specification:
     * - Requires `OrderTransfer.store` to be set.
     * - Iterates over `OrderTransfer.items`.
     * - Does nothing if `ItemTransfer.warehouse.idStock` is set or `ItemTransfer.productOfferReference` is not set.
     * - Finds the first warehouse by provided `ItemTransfer.productOfferReference`, `ItemTransfer.quantity` and `OrderTransfer.store`
     *   from `spy_product_offer_stock` DB table with never out of stock or with the highest quantity.
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
