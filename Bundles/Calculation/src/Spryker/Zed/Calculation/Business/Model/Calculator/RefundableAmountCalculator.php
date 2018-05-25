<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;

class RefundableAmountCalculator implements CalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->calculateRefundableAmountForItems($calculableObjectTransfer->getItems());
        $this->calculateRefundableAmountForExpenses($calculableObjectTransfer->getExpenses());
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return void
     */
    protected function calculateRefundableAmountForItems(ArrayObject $items)
    {
        foreach ($items as $itemTransfer) {
            $itemTransfer->requireUnitPriceToPayAggregation();

            $itemTransfer->setRefundableAmount(
                $itemTransfer->getSumPriceToPayAggregation() - $itemTransfer->getCanceledAmount()
            );
        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenses
     *
     * @return void
     */
    protected function calculateRefundableAmountForExpenses(ArrayObject $expenses)
    {
        foreach ($expenses as $expenseTransfer) {
            $expenseTransfer->setRefundableAmount(
                $expenseTransfer->getSumPriceToPayAggregation() - $expenseTransfer->getCanceledAmount()
            );
        }
    }
}
