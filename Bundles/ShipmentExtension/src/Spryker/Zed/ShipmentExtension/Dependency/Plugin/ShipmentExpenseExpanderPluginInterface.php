<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;

/**
 * Provides shipment expense expand ability before entity is saved to database.
 */
interface ShipmentExpenseExpanderPluginInterface
{
    /**
     * Specification:
     *  - Expands shipment expense with data.
     *  - Plugin stack is called before expense transfer created\updated.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    public function expand(ExpenseTransfer $expenseTransfer, ShipmentGroupTransfer $shipmentGroupTransfer): ExpenseTransfer;
}
