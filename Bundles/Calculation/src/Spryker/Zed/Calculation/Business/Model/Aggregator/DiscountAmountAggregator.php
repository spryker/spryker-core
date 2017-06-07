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
use Spryker\Shared\Calculation\CalculationPriceMode;
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
        $this->calculateDiscountAmountAggregationForItems($calculableObjectTransfer->getItems(), $calculableObjectTransfer->getPriceMode());
        $this->calculateDiscountAmountAggregationForExpenses($calculableObjectTransfer->getExpenses(), $calculableObjectTransfer->getPriceMode());
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     * @param string $priceMode
     *
     * @return void
     */
    protected function calculateDiscountAmountAggregationForItems(ArrayObject $items, $priceMode)
    {
        foreach ($items as $itemTransfer) {

            $this->calculateDiscountAmountForProductOptions($itemTransfer, $priceMode);

            $itemTransfer->setUnitDiscountAmountAggregation(
                $this->calculateUnitDiscountAmountAggregation(
                    $itemTransfer->getCalculatedDiscounts(),
                    $itemTransfer->getUnitPrice(),
                    $priceMode,
                    $itemTransfer->getTaxRate()
                )
            );

            $itemTransfer->setSumDiscountAmountAggregation(
                $this->calculateSumDiscountAmountAggregation(
                    $itemTransfer->getCalculatedDiscounts(),
                    $itemTransfer->getSumPrice(),
                    $priceMode,
                    $itemTransfer->getTaxRate()
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
    protected function calculateDiscountAmountAggregationForExpenses(ArrayObject $expenses, $priceMode)
    {
        foreach ($expenses as $expenseTransfer) {

            $unitDiscountAmountAggregation = $this->calculateUnitDiscountAmountAggregation(
                $expenseTransfer->getCalculatedDiscounts(),
                $expenseTransfer->getUnitPrice(),
                $priceMode,
                $expenseTransfer->getTaxRate()
            );
            $expenseTransfer->setUnitDiscountAmountAggregation($unitDiscountAmountAggregation);

            $sumDiscountAmountAggregation = $this->calculateSumDiscountAmountAggregation(
                $expenseTransfer->getCalculatedDiscounts(),
                $expenseTransfer->getSumPrice(),
                $priceMode,
                $expenseTransfer->getTaxRate()
            );
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
     * @param string $priceMode
     *
     * @return void
     */
    protected function calculateDiscountAmountForProductOptions(ItemTransfer $itemTransfer, $priceMode)
    {
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {

            $productOptionTransfer->setUnitDiscountAmountAggregation(
                $this->calculateUnitDiscountAmountAggregation(
                    $productOptionTransfer->getCalculatedDiscounts(),
                    $productOptionTransfer->getUnitPrice(),
                    $priceMode,
                    $productOptionTransfer->getTaxRate()
                )
            );

            $productOptionTransfer->setSumDiscountAmountAggregation(
                $this->calculateSumDiscountAmountAggregation(
                    $productOptionTransfer->getCalculatedDiscounts(),
                    $productOptionTransfer->getSumPrice(),
                    $priceMode,
                    $productOptionTransfer->getTaxRate()
                )
            );
        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\CalculatedDiscountTransfer[] $calculateDiscounts
     * @param int $maxAmount
     * @param string $priceMode
     * @param int $taxRate
     *
     * @return int
     */
    protected function calculateSumDiscountAmountAggregation(ArrayObject $calculateDiscounts, $maxAmount, $priceMode, $taxRate)
    {
        $itemSumDiscountAmountAggregation = 0;
        foreach ($calculateDiscounts as $calculatedDiscountTransfer) {
            $this->setCalculatedDiscountsSumGrossAmount($calculatedDiscountTransfer);

            $discountAmount = $calculatedDiscountTransfer->getSumGrossAmount();
            if ($priceMode === CalculationPriceMode::PRICE_MODE_NET) {
                $discountAmount = (int)round($calculatedDiscountTransfer->getSumGrossAmount() - ($calculatedDiscountTransfer->getSumGrossAmount() * $taxRate / (100 + $taxRate)));
            }

            $itemSumDiscountAmountAggregation += $discountAmount;
        }

        if ($itemSumDiscountAmountAggregation > $maxAmount) {
            return $maxAmount;
        }

        return $itemSumDiscountAmountAggregation;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\CalculatedDiscountTransfer[] $calculateDiscounts
     * @param int $maxAmount
     * @param string $priceMode
     * @param int $taxRate
     *
     * @return int
     */
    protected function calculateUnitDiscountAmountAggregation(ArrayObject $calculateDiscounts, $maxAmount, $priceMode, $taxRate)
    {
        $itemUnitDiscountAmountAggregation = 0;
        $appliedDiscounts = [];
        foreach ($calculateDiscounts as $calculatedDiscountTransfer) {
            $idDiscount = $calculatedDiscountTransfer->getIdDiscount();
            if (isset($appliedDiscounts[$idDiscount])) {
                continue;
            }

            $discountAmount = $calculatedDiscountTransfer->getUnitGrossAmount();
            if ($priceMode === CalculationPriceMode::PRICE_MODE_NET) {
                $discountAmount = (int)round($calculatedDiscountTransfer->getUnitGrossAmount() - ($calculatedDiscountTransfer->getUnitGrossAmount() * $taxRate / (100 + $taxRate)));
            }

            $itemUnitDiscountAmountAggregation += $discountAmount;
            $appliedDiscounts[$idDiscount] = true;
        }

        if ($itemUnitDiscountAmountAggregation > $maxAmount) {
            return $maxAmount;
        }

        return $itemUnitDiscountAmountAggregation;
    }

}
