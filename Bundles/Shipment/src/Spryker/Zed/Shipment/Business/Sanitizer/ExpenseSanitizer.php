<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Sanitizer;

use Generated\Shared\Transfer\ExpenseTransfer;

/**
 * @deprecated @deprecated For BC reasons the missing sum prices are mirrored from unit prices.
 */
class ExpenseSanitizer implements ExpenseSanitizerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    public function sanitizeExpenseSumValues(ExpenseTransfer $expenseTransfer): ExpenseTransfer
    {
        $expenseTransfer->setSumGrossPrice($expenseTransfer->getSumGrossPrice() ?? $expenseTransfer->getUnitGrossPrice());
        $expenseTransfer->setSumNetPrice($expenseTransfer->getSumNetPrice() ?? $expenseTransfer->getUnitNetPrice());
        $expenseTransfer->setSumPrice($expenseTransfer->getSumPrice() ?? $expenseTransfer->getUnitPrice());
        $expenseTransfer->setSumTaxAmount($expenseTransfer->getSumTaxAmount() ?? $expenseTransfer->getUnitTaxAmount());
        $expenseTransfer->setSumDiscountAmountAggregation(
            $expenseTransfer->getSumDiscountAmountAggregation()
            ?? $expenseTransfer->getUnitDiscountAmountAggregation()
        );
        $expenseTransfer->setSumPriceToPayAggregation(
            $expenseTransfer->getSumPriceToPayAggregation()
            ?? $expenseTransfer->getUnitPriceToPayAggregation()
        );

        return $expenseTransfer;
    }
}
