<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Aggregator;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Spryker\Shared\Calculation\CalculationTaxMode;
use Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface;

class PriceToPayAggregator implements CalculatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->calculatePriceToPayAggregationForItems($calculableObjectTransfer->getItems(), $calculableObjectTransfer->getTaxMode());
        $this->calculatePriceToPayAggregationForExpenses($calculableObjectTransfer->getExpenses(), $calculableObjectTransfer->getTaxMode());
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     * @param string $taxMode
     *
     * @return void
     */
    protected function calculatePriceToPayAggregationForItems(ArrayObject $items, $taxMode)
    {
        foreach ($items as $itemTransfer) {
            $itemTransfer->requireSumSubtotalAggregation()
                ->requireUnitSubtotalAggregation();

            $itemTransfer->setUnitPriceToPayAggregation(
                $this->calculatePriceToPayAggregation(
                    $itemTransfer->getUnitSubtotalAggregation(),
                    $taxMode,
                    $itemTransfer->getUnitDiscountAmountAggregation(),
                    $itemTransfer->getUnitTaxAmountFullAggregation()
                )
            );

            $itemTransfer->setSumPriceToPayAggregation(
                $this->calculatePriceToPayAggregation(
                    $itemTransfer->getSumSubtotalAggregation(),
                    $taxMode,
                    $itemTransfer->getSumDiscountAmountFullAggregation(),
                    $itemTransfer->getSumTaxAmountFullAggregation()
                )
            );

        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenses
     * @param string $taxMode
     *
     * @return void
     */
    protected function calculatePriceToPayAggregationForExpenses(ArrayObject $expenses, $taxMode)
    {
        foreach ($expenses as $expenseTransfer) {

            $expenseTransfer->setUnitPriceToPayAggregation(
                $this->calculatePriceToPayAggregation(
                    $expenseTransfer->getUnitPrice(),
                    $taxMode,
                    $expenseTransfer->getUnitDiscountAmountAggregation(),
                    $expenseTransfer->getUnitTaxAmount()
                )
            );

            $expenseTransfer->setSumPriceToPayAggregation(
                $this->calculatePriceToPayAggregation(
                    $expenseTransfer->getSumPrice(),
                    $taxMode,
                    $expenseTransfer->getSumDiscountAmountAggregation(),
                    $expenseTransfer->getSumTaxAmount()
                )
            );
        }
    }

    /**
     * @param int $price
     * @param string $taxMode
     * @param int $discountAmount
     * @param int $taxAmount
     *
     * @return int
     */
    protected function calculatePriceToPayAggregation($price, $taxMode, $discountAmount = 0, $taxAmount = 0)
    {
        if ($taxMode === CalculationTaxMode::TAX_MODE_NET) {
            return $price + $taxAmount - $discountAmount;
        }

        return $price - $discountAmount;
    }

}
