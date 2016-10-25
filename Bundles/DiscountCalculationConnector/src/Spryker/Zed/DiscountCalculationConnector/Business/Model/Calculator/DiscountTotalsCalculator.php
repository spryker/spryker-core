<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class DiscountTotalsCalculator implements CalculatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $this->assertDiscountTotalRequirements($quoteTransfer);

        $totalDiscountAmount = $this->getDiscountTotalAmount($quoteTransfer);
        $quoteTransfer->getTotals()->setDiscountTotal($totalDiscountAmount);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function getDiscountTotalAmount(QuoteTransfer $quoteTransfer)
    {
        $discountTotalAmount = $this->calculateItemDiscounts($quoteTransfer);
        $discountTotalAmount += $this->calculateExpenseTotalDiscountAmount($quoteTransfer);

        return (int)round($discountTotalAmount);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function calculateItemDiscounts(QuoteTransfer $quoteTransfer)
    {
        $itemTotalDiscountAmount = 0;
        foreach ($quoteTransfer->getItems() as $itemTransfer) {

            $itemDiscountAmount = $this->getItemTotalDiscountAmount($itemTransfer);
            if ($itemDiscountAmount > $itemTransfer->getSumGrossPrice()) {
                $itemDiscountAmount = $itemTransfer->getSumGrossPrice();
            }

            $itemTotalDiscountAmount += $itemDiscountAmount;
        }

        return $itemTotalDiscountAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int
     */
    protected function getItemTotalDiscountAmount(ItemTransfer $itemTransfer)
    {
        return $this->getCalculatedDiscountsSumGrossAmount($itemTransfer->getCalculatedDiscounts());
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\CalculatedDiscountTransfer[] $calculatedDiscounts
     *
     * @return int
     */
    protected function getCalculatedDiscountsSumGrossAmount(\ArrayObject $calculatedDiscounts)
    {
        $totalDiscountSumGrossAmount = 0;
        foreach ($calculatedDiscounts as $calculatedDiscountTransfer) {
            $totalDiscountSumGrossAmount += $calculatedDiscountTransfer->getSumGrossAmount();
        }

        return $totalDiscountSumGrossAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function calculateExpenseTotalDiscountAmount(QuoteTransfer $quoteTransfer)
    {
        $totalDiscountSumGrossAmount = 0;
        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {

            $sumAmount = $this->getCalculatedDiscountsSumGrossAmount($expenseTransfer->getCalculatedDiscounts());
            if ($sumAmount > $expenseTransfer->getSumGrossPrice()) {
                $sumAmount = $expenseTransfer->getSumGrossPrice();
            }

            $totalDiscountSumGrossAmount += $sumAmount;
        }

        return $totalDiscountSumGrossAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculatedDiscountTransfer $calculatedDiscountTransfer
     *
     * @return void
     */
    protected function assertCalculatedDiscountRequirements(CalculatedDiscountTransfer $calculatedDiscountTransfer)
    {
        $calculatedDiscountTransfer->requireQuantity()->requireUnitGrossAmount();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function assertDiscountTotalRequirements(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->requireTotals();
    }

}
