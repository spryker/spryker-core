<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferWarehouseAllocationExample\Communication\Plugin\WarehouseAllocation;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\WarehouseAllocationExtension\Dependency\Plugin\SalesOrderWarehouseAllocationPluginInterface;

/**
 * @method \Spryker\Zed\ProductOfferWarehouseAllocationExample\Business\ProductOfferWarehouseAllocationExampleFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOfferWarehouseAllocationExample\ProductOfferWarehouseAllocationExampleConfig getConfig()
 */
class ProductOfferSalesOrderWarehouseAllocationPlugin extends AbstractPlugin implements SalesOrderWarehouseAllocationPluginInterface
{
    /**
     * {@inheritDoc}
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
    public function allocateWarehouse(OrderTransfer $orderTransfer): OrderTransfer
    {
        return $this->getFacade()->allocateSalesOrderWarehouse($orderTransfer);
    }
}
