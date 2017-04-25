<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Aggregator;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Calculation\Business\Calculator\CalculatorInterface;

class PriceToPayAggregator implements CalculatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->calculatePriceToPayAggregationForItems($calculableObjectTransfer->getItems());
        $this->calculatePriceToPayAggregationForExpenses($calculableObjectTransfer->getExpenses());
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return void
     */
    protected function calculatePriceToPayAggregationForItems(ArrayObject $items)
    {
        foreach ($items as $itemTransfer) {
            $itemTransfer->requireSumSubtotalAggregation()
                ->requireUnitSubtotalAggregation();

            $itemTransfer->setUnitPriceToPayAggregation(
                $itemTransfer->getUnitSubtotalAggregation() - $itemTransfer->getUnitDiscountAmountAggregation()
            );

            $itemTransfer->setSumPriceToPayAggregation(
                $itemTransfer->getSumSubtotalAggregation() - $itemTransfer->getSumDiscountAmountFullAggregation()
            );

        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenses
     *
     * @return void
     */
    protected function calculatePriceToPayAggregationForExpenses(ArrayObject $expenses)
    {
        foreach ($expenses as $expenseTransfer) {

            $expenseTransfer->setUnitPriceToPayAggregation(
                $expenseTransfer->getUnitPrice() - $expenseTransfer->getUnitDiscountAmountAggregation()
            );

            $expenseTransfer->setSumPriceToPayAggregation(
                $expenseTransfer->getSumPrice() - $expenseTransfer->getSumDiscountAmountAggregation()
            );
        }
    }
}
