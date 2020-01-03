<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;

class DiscountTotalCalculator implements CalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $calculableObjectTransfer->requireTotals();

        $totalDiscountAmount = $this->calculateItemTotalDiscountAmount($calculableObjectTransfer->getItems());
        $totalDiscountAmount += $this->calculateExpenseTotalDiscountAmount($calculableObjectTransfer);

        $calculableObjectTransfer->getTotals()->setDiscountTotal($totalDiscountAmount);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return int
     */
    protected function calculateExpenseTotalDiscountAmount(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $totalDiscountAmount = 0;
        foreach ($calculableObjectTransfer->getExpenses() as $expenseTransfer) {
            $totalDiscountAmount += $expenseTransfer->getSumDiscountAmountAggregation();
        }

        return $totalDiscountAmount;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return int
     */
    protected function calculateItemTotalDiscountAmount(ArrayObject $items)
    {
        $totalDiscountAmount = 0;
        foreach ($items as $itemTransfer) {
            $totalDiscountAmount += $itemTransfer->getSumDiscountAmountFullAggregation();
        }

        return $totalDiscountAmount;
    }
}
