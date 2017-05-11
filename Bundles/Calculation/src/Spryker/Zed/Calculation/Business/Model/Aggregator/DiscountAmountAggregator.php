<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Aggregator;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface;

class DiscountAmountAggregator implements CalculatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->calculateDiscountAmountAggregationForItems($calculableObjectTransfer->getItems());
        $this->calculateDiscountAmountAggregationForExpenses($calculableObjectTransfer->getExpenses());
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return void
     */
    protected function calculateDiscountAmountAggregationForItems(ArrayObject $items)
    {
        foreach ($items as $itemTransfer) {

            $this->calculateDiscountAmountForProductOptions($itemTransfer);

            $itemTransfer->setSumDiscountAmountAggregation(
                $this->calculateSumDiscountAmountAggregation($itemTransfer->getCalculatedDiscounts())
            );

            $itemTransfer->setUnitDiscountAmountAggregation(
                $this->calculateUnitDiscountAmountAggregation($itemTransfer->getCalculatedDiscounts())
            );
        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenses
     *
     * @return void
     */
    protected function calculateDiscountAmountAggregationForExpenses(ArrayObject $expenses)
    {
        foreach ($expenses as $expenseTransfer) {

            $unitDiscountAmountAggregation = $this->calculateUnitDiscountAmountAggregation($expenseTransfer->getCalculatedDiscounts());
            $expenseTransfer->setSumDiscountAmountAggregation($unitDiscountAmountAggregation);

            $sumDiscountAmountAggregation = $this->calculateSumDiscountAmountAggregation($expenseTransfer->getCalculatedDiscounts());
            $expenseTransfer->setSumDiscountAmountAggregation($sumDiscountAmountAggregation);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CalculatedDiscountTransfer $calculatedDiscountTransfer
     *
     * @return void
     */
    protected function setCalculatedDiscountsSumGrossAmount(CalculatedDiscountTransfer $calculatedDiscountTransfer)
    {
        $calculatedDiscountTransfer->setSumGrossAmount(
            $calculatedDiscountTransfer->getUnitGrossAmount() * $calculatedDiscountTransfer->getQuantity()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function calculateDiscountAmountForProductOptions(ItemTransfer $itemTransfer)
    {
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {

            $productOptionTransfer->setUnitDiscountAmountAggregation(
                $this->calculateUnitDiscountAmountAggregation($productOptionTransfer->getCalculatedDiscounts())
            );

            $productOptionTransfer->setSumDiscountAmountAggregation(
                $this->calculateSumDiscountAmountAggregation($productOptionTransfer->getCalculatedDiscounts())
            );
        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\CalculatedDiscountTransfer[] $calculateDiscounts
     *
     * @return int
     */
    protected function calculateSumDiscountAmountAggregation(ArrayObject $calculateDiscounts)
    {
        $itemSumDiscountAmountAggregation = 0;
        foreach ($calculateDiscounts as $calculatedDiscountTransfer) {
            $this->setCalculatedDiscountsSumGrossAmount($calculatedDiscountTransfer);
            $itemSumDiscountAmountAggregation += $calculatedDiscountTransfer->getSumGrossAmount();
        }

        return $itemSumDiscountAmountAggregation;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\CalculatedDiscountTransfer[] $calculateDiscounts
     *
     * @return int
     */
    protected function calculateUnitDiscountAmountAggregation(ArrayObject $calculateDiscounts)
    {
        $itemUnitDiscountAmountAggregation = 0;
        $appliedDiscounts = [];
        foreach ($calculateDiscounts as $calculatedDiscountTransfer) {
            $idDiscount = $calculatedDiscountTransfer->getIdDiscount();
            if (isset($appliedDiscounts[$idDiscount])) {
                continue;
            }
            $itemUnitDiscountAmountAggregation += $calculatedDiscountTransfer->getUnitGrossAmount();
            $appliedDiscounts[$idDiscount] = true;
        }

        return $itemUnitDiscountAmountAggregation;
    }

}
