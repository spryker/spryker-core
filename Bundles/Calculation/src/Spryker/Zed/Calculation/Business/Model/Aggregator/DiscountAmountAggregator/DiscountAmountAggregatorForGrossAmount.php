<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Aggregator\DiscountAmountAggregator;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Shared\Calculation\CalculationPriceMode;
use Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface;

/**
 * Old implementation of DiscountAmountAggregation not used since Discount module version 5, kept for BC reasons.
 *
 * @deprecated Use Spryker\Zed\Calculation\Business\Model\Aggregator\DiscountAmountAggregator instead.
 */
class DiscountAmountAggregatorForGrossAmount implements CalculatorInterface
{
    /**
     * @var int[]
     */
    protected $voucherDiscountTotals = [];

    /**
     * @var int[]
     */
    protected $cartRuleDiscountTotals = [];

    /**
     * @var bool
     */
    protected $isOrder = false;

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->isOrder = $calculableObjectTransfer->getOriginalOrder() ? true : false;

        $this->calculateDiscountAmountAggregationForItems(
            $calculableObjectTransfer->getItems(),
            $calculableObjectTransfer->getPriceMode()
        );
        $this->calculateDiscountAmountAggregationForExpenses(
            $calculableObjectTransfer->getExpenses(),
            $calculableObjectTransfer->getPriceMode()
        );

        $this->updateDiscountTotals($calculableObjectTransfer);
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
                    $itemTransfer->getSumPrice()
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
                $expenseTransfer->getSumPrice()
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

        $this->setCalculatedDiscounts($calculatedDiscountTransfer);
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
                    $productOptionTransfer->getSumPrice()
                )
            );
        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\CalculatedDiscountTransfer[] $calculateDiscounts
     * @param int $maxAmount
     *
     * @return int
     */
    protected function calculateSumDiscountAmountAggregation(ArrayObject $calculateDiscounts, $maxAmount)
    {
        $itemSumDiscountAmountAggregation = 0;
        foreach ($calculateDiscounts as $calculatedDiscountTransfer) {
            $this->setCalculatedDiscountsSumGrossAmount($calculatedDiscountTransfer);
            $discountAmount = $calculatedDiscountTransfer->getSumGrossAmount();

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
    protected function calculateUnitDiscountAmountAggregation(
        ArrayObject $calculateDiscounts,
        $maxAmount,
        $priceMode,
        $taxRate
    ) {
        $itemUnitDiscountAmountAggregation = 0;
        $appliedDiscounts = [];
        foreach ($calculateDiscounts as $calculatedDiscountTransfer) {
            $idDiscount = $calculatedDiscountTransfer->getIdDiscount();

            $discountAmount = $calculatedDiscountTransfer->getUnitGrossAmount();
            if ($priceMode === CalculationPriceMode::PRICE_MODE_NET && $this->isOrder === false) {
                $discountAmount = $this->calculateNetDiscountAmount($taxRate, $calculatedDiscountTransfer->getUnitGrossAmount());
                $calculatedDiscountTransfer->setUnitGrossAmount($discountAmount);
            }

            if (isset($appliedDiscounts[$idDiscount])) {
                continue;
            }

            $itemUnitDiscountAmountAggregation += $discountAmount;
            $appliedDiscounts[$idDiscount] = true;
        }

        if ($itemUnitDiscountAmountAggregation > $maxAmount) {
            return $maxAmount;
        }

        return $itemUnitDiscountAmountAggregation;
    }

    /**
     * @param int $taxRate
     * @param int $discountAmount
     *
     * @return int
     */
    protected function calculateNetDiscountAmount($taxRate, $discountAmount)
    {
        return (int)round($discountAmount - ($discountAmount * $taxRate / (100 + $taxRate)));
    }

    /**
     * @param \Generated\Shared\Transfer\CalculatedDiscountTransfer $calculatedDiscountTransfer
     *
     * @return void
     */
    protected function setCalculatedDiscounts(CalculatedDiscountTransfer $calculatedDiscountTransfer)
    {
        $idDiscount = $calculatedDiscountTransfer->getIdDiscount();
        $discountAmount = $calculatedDiscountTransfer->getSumGrossAmount();

        if ($calculatedDiscountTransfer->getVoucherCode()) {
            if (!isset($this->voucherDiscountTotals[$idDiscount])) {
                $this->voucherDiscountTotals[$idDiscount] = $discountAmount;
            } else {
                $this->voucherDiscountTotals[$idDiscount] += $discountAmount;
            }
            return;
        }

        if (!isset($this->cartRuleDiscountTotals[$idDiscount])) {
            $this->cartRuleDiscountTotals[$idDiscount] = $discountAmount;
        } else {
            $this->cartRuleDiscountTotals[$idDiscount] += $discountAmount;
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    protected function updateDiscountTotals(CalculableObjectTransfer $calculableObjectTransfer)
    {
        if ($calculableObjectTransfer->getPriceMode() === CalculationPriceMode::PRICE_MODE_GROSS) {
            return;
        }

        foreach ($calculableObjectTransfer->getCartRuleDiscounts() as $discountTransfer) {
            if (isset($this->cartRuleDiscountTotals[$discountTransfer->getIdDiscount()])) {
                $discountTransfer->setAmount(
                    $this->cartRuleDiscountTotals[$discountTransfer->getIdDiscount()]
                );
            }
        }

        foreach ($calculableObjectTransfer->getVoucherDiscounts() as $discountTransfer) {
            if (isset($this->voucherDiscountTotals[$discountTransfer->getIdDiscount()])) {
                $discountTransfer->setAmount(
                    $this->voucherDiscountTotals[$discountTransfer->getIdDiscount()]
                );
            }
        }
    }
}
