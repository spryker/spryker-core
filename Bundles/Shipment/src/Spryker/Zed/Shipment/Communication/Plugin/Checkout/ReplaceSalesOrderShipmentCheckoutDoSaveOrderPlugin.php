<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutDoSaveOrderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Shipment\ShipmentConfig getConfig()
 * @method \Spryker\Zed\Shipment\Business\ShipmentFacadeInterface getFacade()
 * @method \Spryker\Zed\Shipment\Communication\ShipmentCommunicationFactory getFactory()
 *
 * Plugin should be executed after {@ling \Spryker\Zed\Discount\Communication\Plugin\Checkout\ReplaceSalesOrderDiscountsCheckoutDoSaveOrderPlugin}.
 */
class ReplaceSalesOrderShipmentCheckoutDoSaveOrderPlugin extends AbstractPlugin implements CheckoutDoSaveOrderInterface
{
    /**
     * {@inheritDoc}
     * - Requires `SaveOrderTransfer.idSalesOrder` to be set.
     * - Sets `spy_sales_order_item.fk_sales_shipment` to null for all items related to the provided sales order.
     * - Deletes sales shipments related to the provided sales order.
     * - Deletes sales shipment expenses related to the provided sales order.
     * - Recreates new sales shipment expenses for each item level shipment.
     * - Executes a stack of {@link \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentExpenseExpanderPluginInterface} plugins before the expense saving.
     * - Creates new sales order addresses for each item level shipment.
     * - Recreates new sales shipments for the provided sales order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        $this->getFacade()->replaceSalesOrderShipment($quoteTransfer, $saveOrderTransfer);
    }
}
