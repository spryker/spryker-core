<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Communication\Plugin\Shipment;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentExpenseExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantSalesOrder\MerchantSalesOrderConfig getConfig()
 * @method \Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrderFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantSalesOrder\Communication\MerchantSalesOrderCommunicationFactory getFactory()
 */
class MerchantReferenceShipmentExpenseExpanderPlugin extends AbstractPlugin implements ShipmentExpenseExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands expense transfer with merchant reference from items.
     * - Doesn't expand if items have different merchant references or given expense is not of shipment type.
     * - Requires ShipmentGroup.items property to be set.
     * - Returns expanded expense transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    public function expand(ExpenseTransfer $expenseTransfer, ShipmentGroupTransfer $shipmentGroupTransfer): ExpenseTransfer
    {
        return $this->getFacade()->expandShipmentExpenseWithMerchantReference($expenseTransfer, $shipmentGroupTransfer);
    }
}
