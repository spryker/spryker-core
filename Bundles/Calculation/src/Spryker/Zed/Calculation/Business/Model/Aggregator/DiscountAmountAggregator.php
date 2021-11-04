<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Aggregator;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\CalculatedDiscountCollectionTransfer;
use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Calculation\Business\Filter\CalculatedDiscountFilterInterface;
use Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface;

class DiscountAmountAggregator implements CalculatorInterface
{
    /**
     * @var int
     */
    protected const DEFAULT_AMOUNT = 0;

    /**
     * @var array<int>
     */
    protected $voucherDiscountTotals = [];

    /**
     * @var array<int>
     */
    protected $cartRuleDiscountTotals = [];

    /**
     * @var \Spryker\Zed\Calculation\Business\Filter\CalculatedDiscountFilterInterface
     */
    protected $calculatedDiscountFilter;

    /**
     * @param \Spryker\Zed\Calculation\Business\Filter\CalculatedDiscountFilterInterface $calculatedDiscountFilter
     */
    public function __construct(CalculatedDiscountFilterInterface $calculatedDiscountFilter)
    {
        $this->calculatedDiscountFilter = $calculatedDiscountFilter;
    }

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
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $items
     *
     * @return void
     */
    protected function calculateDiscountAmountAggregationForItems(ArrayObject $items)
    {
        foreach ($items as $itemTransfer) {
            $this->calculateDiscountAmountForProductOptions($itemTransfer);
            $calculatedDiscountTransfers = $itemTransfer->getCalculatedDiscounts();

            $itemTransfer->setUnitDiscountAmountAggregation(
                $this->calculateUnitDiscountAmountAggregation(
                    $calculatedDiscountTransfers,
                    $itemTransfer->getUnitPrice(),
                ),
            );

            $itemTransfer->setSumDiscountAmountAggregation(
                $this->calculateSumDiscountAmountAggregation(
                    $calculatedDiscountTransfers,
                    $itemTransfer->getSumPrice(),
                ),
            );

            $filteredCalculatedDiscountCollection = $this->calculatedDiscountFilter->filterOutEmptyCalculatedDiscounts(
                (new CalculatedDiscountCollectionTransfer())
                    ->setCalculatedDiscounts($calculatedDiscountTransfers),
            );

            $itemTransfer->setCalculatedDiscounts(
                $filteredCalculatedDiscountCollection->getCalculatedDiscounts(),
            );
        }
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ExpenseTransfer> $expenses
     *
     * @return void
     */
    protected function calculateDiscountAmountAggregationForExpenses(ArrayObject $expenses)
    {
        foreach ($expenses as $expenseTransfer) {
            $calculatedDiscountTransfers = $expenseTransfer->getCalculatedDiscounts();

            $expenseTransfer->setUnitDiscountAmountAggregation(
                $this->calculateUnitDiscountAmountAggregation(
                    $calculatedDiscountTransfers,
                    $expenseTransfer->getUnitPrice(),
                ),
            );

            $expenseTransfer->setSumDiscountAmountAggregation(
                $this->calculateSumDiscountAmountAggregation(
                    $calculatedDiscountTransfers,
                    $expenseTransfer->getSumPrice(),
                ),
            );

            $filteredCalculatedDiscountCollection = $this->calculatedDiscountFilter->filterOutEmptyCalculatedDiscounts(
                (new CalculatedDiscountCollectionTransfer())
                    ->setCalculatedDiscounts($calculatedDiscountTransfers),
            );

            $expenseTransfer->setCalculatedDiscounts(
                $filteredCalculatedDiscountCollection->getCalculatedDiscounts(),
            );
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
            $calculatedDiscountTransfers = $productOptionTransfer->getCalculatedDiscounts();

            $productOptionTransfer->setUnitDiscountAmountAggregation(
                $this->calculateUnitDiscountAmountAggregation(
                    $calculatedDiscountTransfers,
                    $productOptionTransfer->getUnitPrice(),
                ),
            );

            $productOptionTransfer->setSumDiscountAmountAggregation(
                $this->calculateSumDiscountAmountAggregation(
                    $calculatedDiscountTransfers,
                    $productOptionTransfer->getSumPrice(),
                ),
            );

            $filteredCalculatedDiscountCollection = $this->calculatedDiscountFilter->filterOutEmptyCalculatedDiscounts(
                (new CalculatedDiscountCollectionTransfer())
                    ->setCalculatedDiscounts($calculatedDiscountTransfers),
            );

            $productOptionTransfer->setCalculatedDiscounts(
                $filteredCalculatedDiscountCollection->getCalculatedDiscounts(),
            );
        }
    }

    /**
     * @deprecated For BC reasons, the sum prices are populated in case if they are not set
     *
     * @param \Generated\Shared\Transfer\CalculatedDiscountTransfer $calculatedDiscountTransfer
     *
     * @return void
     */
    protected function sanitizeCalculatedDiscountSumPrices(CalculatedDiscountTransfer $calculatedDiscountTransfer)
    {
        if (!$calculatedDiscountTransfer->getSumAmount()) {
            $calculatedDiscountTransfer->setSumAmount(
                $calculatedDiscountTransfer->getUnitAmount() * $calculatedDiscountTransfer->getQuantity(),
            );
        }
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\CalculatedDiscountTransfer> $calculateDiscounts
     * @param int $maxAmount
     *
     * @return int
     */
    protected function calculateSumDiscountAmountAggregation(ArrayObject $calculateDiscounts, $maxAmount)
    {
        $itemSumDiscountAmountAggregation = static::DEFAULT_AMOUNT;
        foreach ($calculateDiscounts as $calculatedDiscountTransfer) {
            $this->sanitizeCalculatedDiscountSumPrices($calculatedDiscountTransfer);

            $discountAmountToApply = $this->getDiscountAmountToApply(
                $calculatedDiscountTransfer->getSumAmount(),
                $itemSumDiscountAmountAggregation,
                (int)$maxAmount,
            );

            $itemSumDiscountAmountAggregation += $discountAmountToApply;
            $calculatedDiscountTransfer->setSumAmount($discountAmountToApply);

            if ($discountAmountToApply) {
                $this->setCalculatedDiscounts($calculatedDiscountTransfer, $discountAmountToApply);
            }
        }

        if ($itemSumDiscountAmountAggregation > $maxAmount) {
            return $maxAmount;
        }

        return $itemSumDiscountAmountAggregation;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\CalculatedDiscountTransfer> $calculateDiscounts
     * @param int $maxAmount
     *
     * @return int
     */
    protected function calculateUnitDiscountAmountAggregation(ArrayObject $calculateDiscounts, $maxAmount)
    {
        $itemUnitDiscountAmountAggregation = static::DEFAULT_AMOUNT;
        $appliedDiscounts = [];
        foreach ($calculateDiscounts as $calculatedDiscountTransfer) {
            $idDiscount = $calculatedDiscountTransfer->getIdDiscount();
            if (isset($appliedDiscounts[$idDiscount])) {
                continue;
            }

            $discountAmountToApply = $this->getDiscountAmountToApply(
                (int)$calculatedDiscountTransfer->getUnitAmount(),
                $itemUnitDiscountAmountAggregation,
                (int)$maxAmount,
            );

            $itemUnitDiscountAmountAggregation += $discountAmountToApply;
            $calculatedDiscountTransfer->setUnitAmount($discountAmountToApply);

            $appliedDiscounts[$idDiscount] = true;
        }

        if ($itemUnitDiscountAmountAggregation > $maxAmount) {
            return $maxAmount;
        }

        return $itemUnitDiscountAmountAggregation;
    }

    /**
     * @param int $discountAmount
     * @param int $itemAggregatedAmount
     * @param int $maxAmount
     *
     * @return int
     */
    protected function getDiscountAmountToApply(int $discountAmount, int $itemAggregatedAmount, int $maxAmount): int
    {
        $itemAggregatedAmount += $discountAmount;
        if ($itemAggregatedAmount <= $maxAmount) {
            return $discountAmount;
        }

        $appliedDiscountAmount = $maxAmount - ($itemAggregatedAmount - $discountAmount);
        if ($appliedDiscountAmount) {
            return $appliedDiscountAmount;
        }

        return static::DEFAULT_AMOUNT;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculatedDiscountTransfer $calculatedDiscountTransfer
     * @param int $discountAmount
     *
     * @return void
     */
    protected function setCalculatedDiscounts(CalculatedDiscountTransfer $calculatedDiscountTransfer, int $discountAmount): void
    {
        $idDiscount = $calculatedDiscountTransfer->getIdDiscount();

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
                    $this->cartRuleDiscountTotals[$discountTransfer->getIdDiscount()],
                );

                continue;
            }

            $discountTransfer->setAmount(static::DEFAULT_AMOUNT);
        }

        foreach ($calculableObjectTransfer->getVoucherDiscounts() as $discountTransfer) {
            if (isset($this->voucherDiscountTotals[$discountTransfer->getIdDiscount()])) {
                $discountTransfer->setAmount(
                    $this->voucherDiscountTotals[$discountTransfer->getIdDiscount()],
                );

                continue;
            }

            $discountTransfer->setAmount(static::DEFAULT_AMOUNT);
        }
    }
}
