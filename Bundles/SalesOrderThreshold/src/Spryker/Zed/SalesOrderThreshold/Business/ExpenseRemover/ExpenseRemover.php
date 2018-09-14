<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Business\ExpenseRemover;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig;

class ExpenseRemover implements ExpenseRemoverInterface
{
    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function removeSalesOrderThresholdExpenses(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        foreach ($calculableObjectTransfer->getExpenses() as $expenseOffset => $expenseTransfer) {
            if ($expenseTransfer->getType() === SalesOrderThresholdConfig::THRESHOLD_EXPENSE_TYPE) {
                $calculableObjectTransfer->getExpenses()->offsetUnset($expenseOffset);
                continue;
            }
        }
    }
}
