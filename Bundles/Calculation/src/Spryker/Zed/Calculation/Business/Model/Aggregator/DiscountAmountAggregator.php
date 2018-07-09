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
     * @var int[]
     */
    protected $voucherDiscountTotals = [];

    /**
     * @var int[]
     */
    protected $cartRuleDiscountTotals = [];

    /**
     * Unit prices are populated for presentation purposes only. For further calculations use sum prices are properly populated unit prices.
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->calculateDiscountAmountAggregationForItems($calculableObjectTransfer->getItems());
        $this->calculateDiscountAmountAggregationForExpenses($calculableObjectTransfer->getExpenses());

        $this->updateDiscountTotals($calculableObjectTransfer);
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

            $itemTransfer->setUnitDiscountAmountAggregation(
                $this->calculateUnitDiscountAmountAggregation(
                    $itemTransfer->getCalculatedDiscounts(),
                    $itemTransfer->getUnitPrice()
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
     *
     * @return void
     */
    protected function calculateDiscountAmountAggregationForExpenses(ArrayObject $expenses)
    {
        foreach ($expenses as $expenseTransfer) {
            $expenseTransfer->setUnitDiscountAmountAggregation(
                $this->calculateUnitDiscountAmountAggregation(
                    $expenseTransfer->getCalculatedDiscounts(),
                    $expenseTransfer->getUnitPrice()
                )
            );

            $sumDiscountAmountAggregation = $this->calculateSumDiscountAmountAggregation(
                $expenseTransfer->getCalculatedDiscounts(),
                $expenseTransfer->getSumPrice()
            );
            $expenseTransfer->setSumDiscountAmountAggregation($sumDiscountAmountAggregation);
        }
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
                $this->calculateUnitDiscountAmountAggregation(
                    $productOptionTransfer->getCalculatedDiscounts(),
                    $productOptionTransfer->getUnitPrice()
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
            $this->setCalculatedDiscounts($calculatedDiscountTransfer);

            $discountAmount = $calculatedDiscountTransfer->getSumAmount();

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
     *
     * @return int
     */
    protected function calculateUnitDiscountAmountAggregation(ArrayObject $calculateDiscounts, $maxAmount)
    {
        $itemUnitDiscountAmountAggregation = 0;
        $appliedDiscounts = [];
        foreach ($calculateDiscounts as $calculatedDiscountTransfer) {
            $this->deriveUnitAmount($calculatedDiscountTransfer);

            $idDiscount = $calculatedDiscountTransfer->getIdDiscount();
            if (isset($appliedDiscounts[$idDiscount])) {
                continue;
            }

            $derivedUnitAmount = $calculatedDiscountTransfer->getUnitAmount();

            $itemUnitDiscountAmountAggregation += $derivedUnitAmount;
            $appliedDiscounts[$idDiscount] = true;
        }

        if ($itemUnitDiscountAmountAggregation > $maxAmount) {
            return $maxAmount;
        }

        return $itemUnitDiscountAmountAggregation;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculatedDiscountTransfer $calculatedDiscountTransfer
     *
     * @return void
     */
    protected function deriveUnitAmount(CalculatedDiscountTransfer $calculatedDiscountTransfer)
    {
        $calculatedDiscountTransfer->setUnitAmount((int)round($calculatedDiscountTransfer->getSumAmount() / $calculatedDiscountTransfer->getQuantity()));
    }

    /**
     * @param \Generated\Shared\Transfer\CalculatedDiscountTransfer $calculatedDiscountTransfer
     *
     * @return void
     */
    protected function setCalculatedDiscounts(CalculatedDiscountTransfer $calculatedDiscountTransfer)
    {
        $idDiscount = $calculatedDiscountTransfer->getIdDiscount();
        $discountAmount = $calculatedDiscountTransfer->getSumAmount();

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
