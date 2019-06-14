<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Sanitizer;

use Generated\Shared\Transfer\ExpenseTransfer;

/**
 * @deprecated For BC reasons the missing sum prices are mirrored from unit prices.
 */
interface ExpenseSanitizerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    public function sanitizeExpenseSumValues(ExpenseTransfer $expenseTransfer): ExpenseTransfer;
}
