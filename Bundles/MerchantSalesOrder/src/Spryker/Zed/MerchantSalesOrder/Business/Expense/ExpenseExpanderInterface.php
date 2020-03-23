<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Business\Expense;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;

interface ExpenseExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    public function expandShipmentExpenseWithMerchantReference(
        ExpenseTransfer $expenseTransfer,
        ShipmentGroupTransfer $shipmentGroupTransfer
    ): ExpenseTransfer;
}
