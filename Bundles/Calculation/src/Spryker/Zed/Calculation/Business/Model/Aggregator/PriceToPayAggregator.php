<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Aggregator;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Shared\Calculation\CalculationPriceMode;
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
        $this->calculatePriceToPayAggregationForItems($calculableObjectTransfer->getItems(), $calculableObjectTransfer->getPriceMode());
        $this->calculatePriceToPayAggregationForExpenses($calculableObjectTransfer->getExpenses(), $calculableObjectTransfer->getPriceMode());
        $this->calculatePriceToPayAggregationForItemExpenses($calculableObjectTransfer->getItems(), $calculableObjectTransfer->getPriceMode());
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     * @param string $priceMode
     *
     * @return void
     */
    protected function calculatePriceToPayAggregationForItems(ArrayObject $items, $priceMode)
    {
        foreach ($items as $itemTransfer) {
            $itemTransfer->requireSumSubtotalAggregation()
                ->requireUnitSubtotalAggregation();

            $itemTransfer->setUnitPriceToPayAggregation(
                $this->calculatePriceToPayAggregation(
                    $itemTransfer->getUnitSubtotalAggregation(),
                    $priceMode,
                    $itemTransfer->getUnitDiscountAmountAggregation(),
                    $itemTransfer->getUnitTaxAmountFullAggregation()
                )
            );

            $itemTransfer->setSumPriceToPayAggregation(
                $this->calculatePriceToPayAggregation(
                    $itemTransfer->getSumSubtotalAggregation(),
                    $priceMode,
                    $itemTransfer->getSumDiscountAmountFullAggregation(),
                    $itemTransfer->getSumTaxAmountFullAggregation()
                )
            );
        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenses
     * @param string $priceMode
     *
     * @return void
     */
    protected function calculatePriceToPayAggregationForExpenses(ArrayObject $expenses, $priceMode)
    {
        foreach ($expenses as $expenseTransfer) {
            $expenseTransfer->setUnitPriceToPayAggregation(
                $this->calculatePriceToPayAggregation(
                    $expenseTransfer->getUnitPrice(),
                    $priceMode,
                    $expenseTransfer->getUnitDiscountAmountAggregation(),
                    $expenseTransfer->getUnitTaxAmount()
                )
            );

            $expenseTransfer->setSumPriceToPayAggregation(
                $this->calculatePriceToPayAggregation(
                    $expenseTransfer->getSumPrice(),
                    $priceMode,
                    $expenseTransfer->getSumDiscountAmountAggregation(),
                    $expenseTransfer->getSumTaxAmount()
                )
            );
        }
    }

    /**
     * @param int $price
     * @param string $priceMode
     * @param int $discountAmount
     * @param int $taxAmount
     *
     * @return int
     */
    protected function calculatePriceToPayAggregation($price, $priceMode, $discountAmount = 0, $taxAmount = 0)
    {
        if ($priceMode === CalculationPriceMode::PRICE_MODE_NET) {
            return $price + $taxAmount - $discountAmount;
        }

        return $price - $discountAmount;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     * @param string $priceMode
     *
     * @return void
     */
    protected function calculatePriceToPayAggregationForItemExpenses(ArrayObject $items, string $priceMode): void
    {
        $expenses = $this->getItemExpenses($items);
        $this->calculatePriceToPayAggregationForExpenses($expenses, $priceMode);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[]
     */
    protected function getItemExpenses(ArrayObject $items): ArrayObject
    {
        $expenses = new ArrayObject();
        foreach ($items as $itemTransfer) {
            if ($this->assertItemHasNoExpenseRequirements($itemTransfer)) {
                continue;
            }

            $expenses->append($itemTransfer->getShipment()->getExpense());
        }

        return $expenses;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function assertItemHasNoExpenseRequirements(ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getShipment() === null || $itemTransfer->getShipment()->getExpense() === null;
    }
}
