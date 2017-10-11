<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Aggregator;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
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

}
