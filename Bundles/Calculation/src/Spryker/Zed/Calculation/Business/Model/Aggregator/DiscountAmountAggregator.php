<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Aggregator;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
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

            // BC: Unit price is populated for BC reasons only
            $this->setItemTransferUnitDiscountAmountAggregation($itemTransfer);

            $itemTransfer->setSumDiscountAmountAggregation(
                $this->calculateSumDiscountAmountAggregation(
                    $itemTransfer->getCalculatedDiscounts(),
                    $itemTransfer->getSumPrice()
                )
            );
        }
    }

    /**
     * @deprecated Uses derived unit price which is accurate for quantity = 1 only
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function setItemTransferUnitDiscountAmountAggregation(ItemTransfer $itemTransfer)
    {
        // BC: When ItemTransfer is populated from Persistence, sum price is accurate and populated, unit price is derived
        //$derivedUnitPrice = (int)round($itemTransfer->getSumPrice() / $itemTransfer->getQuantity());
        $derivedUnitPrice = $itemTransfer->getUnitPrice();

        $itemTransfer->setUnitDiscountAmountAggregation(
            $this->calculateUnitDiscountAmountAggregation(
                $itemTransfer->getCalculatedDiscounts(),
                $derivedUnitPrice
            )
        );
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenses
     *
     * @return void
     */
    protected function calculateDiscountAmountAggregationForExpenses(ArrayObject $expenses)
    {
        foreach ($expenses as $expenseTransfer) {
            // BC: Unit price is populated for BC reasons only
            $this->setExpenseTransferUnitDiscountAmountAggregation($expenseTransfer);

            $sumDiscountAmountAggregation = $this->calculateSumDiscountAmountAggregation(
                $expenseTransfer->getCalculatedDiscounts(),
                $expenseTransfer->getSumPrice()
            );
            $expenseTransfer->setSumDiscountAmountAggregation($sumDiscountAmountAggregation);
        }
    }

    /**
     * @deprecated Uses derived unit price which is accurate for quantity = 1 only
     *
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return void
     */
    protected function setExpenseTransferUnitDiscountAmountAggregation(ExpenseTransfer $expenseTransfer)
    {
        // BC: When ExpenseTransfer is populated from Persistence, sum price is accurate and populated, unit price is derived
        //$derivedUnitPrice = (int)round($expenseTransfer->getSumPrice() / $expenseTransfer->getQuantity());
        $derivedUnitPrice = $expenseTransfer->getUnitPrice();

        $expenseTransfer->setUnitDiscountAmountAggregation(
            $this->calculateUnitDiscountAmountAggregation(
                $expenseTransfer->getCalculatedDiscounts(),
                $derivedUnitPrice
            )
        );
    }

    /**
     * @deprecated Uses derived unit price which is accurate for quantity = 1 only
     *
     * @param \Generated\Shared\Transfer\CalculatedDiscountTransfer $calculatedDiscountTransfer
     *
     * @return void
     */
    protected function setCalculatedDiscountTransferUnitPrices(CalculatedDiscountTransfer $calculatedDiscountTransfer)
    {
        $calculatedDiscountTransfer->setUnitAmount((int)round($calculatedDiscountTransfer->getSumAmount() / $calculatedDiscountTransfer->getQuantity()));
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function calculateDiscountAmountForProductOptions(ItemTransfer $itemTransfer)
    {
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            // BC: Unit price is populated for BC reasons only
            $this->setProductOptionTransferUnitDiscountAmountAggregation($productOptionTransfer);

            $productOptionTransfer->setSumDiscountAmountAggregation(
                $this->calculateSumDiscountAmountAggregation(
                    $productOptionTransfer->getCalculatedDiscounts(),
                    $productOptionTransfer->getSumPrice()
                )
            );
        }
    }

    /**
     * @deprecated Uses derived unit price which is accurate for quantity = 1 only
     *
     * @param \Generated\Shared\Transfer\ProductOptionTransfer $productOptionTransfer
     *
     * @return void
     */
    protected function setProductOptionTransferUnitDiscountAmountAggregation(ProductOptionTransfer $productOptionTransfer)
    {
        // BC: When ProductOptionTransfer is populated from Persistence, sum price is accurate and populated, unit price is derived
        //$derivedUnitPrice = (int)round($productOptionTransfer->getSumPrice() / $productOptionTransfer->getQuantity());
        $derivedUnitPrice = $productOptionTransfer->getUnitPrice();

        $productOptionTransfer->setUnitDiscountAmountAggregation(
            $this->calculateUnitDiscountAmountAggregation(
                $productOptionTransfer->getCalculatedDiscounts(),
                $derivedUnitPrice
            )
        );
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
            // BC: When discounts are populated from Persistence, sum price is accurate and populated, unit price is derived
            $this->setCalculatedDiscountTransferUnitPrices($calculatedDiscountTransfer);
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
     * @deprecated Uses derived unit amount which is accurate for quantity = 1 only
     *
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
            $idDiscount = $calculatedDiscountTransfer->getIdDiscount();
            if (isset($appliedDiscounts[$idDiscount])) {
                continue;
            }

            // BC: When discounts are populated from Persistence, sum price is accurate and populated, unit price is derived
            //$derivedDiscountAmount = (int)round($calculatedDiscountTransfer->getSumAmount() / $calculatedDiscountTransfer->getQuantity());
            $derivedDiscountAmount = $calculatedDiscountTransfer->getUnitAmount();

            $itemUnitDiscountAmountAggregation += $derivedDiscountAmount;
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
