<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Sanitizer;

use Generated\Shared\Transfer\ExpenseTransfer;

interface ExpenseSanitizerInterface
{
    /**
     * @deprecated @deprecated For BC reasons the missing sum prices are mirrored from unit prices.
     *
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    public function sanitizeExpenseSumValues(ExpenseTransfer $expenseTransfer): ExpenseTransfer;

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $shipmentExpenseTransfer
     * @param int $price
     * @param string $priceMode
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    public function sanitizeShipmentExpensePricesByPriceMode(ExpenseTransfer $shipmentExpenseTransfer, int $price, string $priceMode): ExpenseTransfer;
}
