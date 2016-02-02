<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class SumGrossCalculatedDiscountAmountCalculator implements CalculatorInterface
{

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $this->calculateItemGrossAmounts($quoteTransfer);
        $this->setExpenseGrossAmounts($quoteTransfer);
    }

    /**
     * @param ItemTransfer $itemTransfer
     *
     * @return int
     */
    protected function setItemGrossAmounts(ItemTransfer $itemTransfer)
    {
        $this->setCalculatedDiscountsSumGrossAmount($itemTransfer->getCalculatedDiscounts());

        $totalDiscountUnitGrossAmount = $this->getCalculatedDiscountsUnitGrossAmount($itemTransfer->getCalculatedDiscounts());
        $totalDiscountSumGrossAmount = $this->getCalculatedDiscountsSumGrossAmount($itemTransfer->getCalculatedDiscounts());

        $totalDiscountUnitGrossAmount += $this->getProductOptionGrossUnitTotalAmount($itemTransfer->getProductOptions());
        $totalDiscountSumGrossAmount += $this->getProductOptionGrossSumTotalAmount($itemTransfer->getProductOptions());

        $itemTransfer->setSumGrossPriceWithProductOptionAndDiscountAmounts(
            $itemTransfer->getSumGrossPriceWithProductOptions() - $totalDiscountSumGrossAmount
        );

        $itemTransfer->setUnitGrossPriceWithProductOptionAndDiscountAmounts(
            $itemTransfer->getUnitGrossPriceWithProductOptions() - $totalDiscountUnitGrossAmount
        );
    }

    /**
     * @param \ArrayObject|CalculatedDiscountTransfer[] $calculatedDiscounts
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
     * @param \ArrayObject|CalculatedDiscountTransfer[] $calculatedDiscounts
     *
     * @return int
     */
    protected function getCalculatedDiscountsUnitGrossAmount(\ArrayObject $calculatedDiscounts)
    {
        $totalDiscountUnitGrossAmount = 0;
        foreach ($calculatedDiscounts as $calculatedDiscountTransfer) {
            $totalDiscountUnitGrossAmount += $calculatedDiscountTransfer->getUnitGrossAmount();
        }
        return $totalDiscountUnitGrossAmount;
    }

    /**
     * @param \ArrayObject|CalculatedDiscountTransfer[] $calculatedDiscounts
     *
     * @return void
     */
    protected function setCalculatedDiscountsSumGrossAmount(\ArrayObject $calculatedDiscounts)
    {
        foreach ($calculatedDiscounts as $calculatedDiscountTransfer) {
            $this->assertCalculatedDiscountRequirements($calculatedDiscountTransfer);
            $calculatedDiscountTransfer->setSumGrossAmount(
                $calculatedDiscountTransfer->getUnitGrossAmount() * $calculatedDiscountTransfer->getQuantity()
            );
        }
    }

    /**
     * @param \ArrayObject|ProductOptionTransfer[] $productOptionTransfer
     *
     * @return int
     */
    protected function getSumOfProductOptionCalculatedDiscounts(\ArrayObject $productOptionTransfer)
    {
        $totalDiscountUnitGrossAmount = 0;
        $totalDiscountSumGrossAmount = 0;
        foreach ($productOptionTransfer as $optionTransfer) {
            $this->setCalculatedDiscountsSumGrossAmount($optionTransfer->getCalculatedDiscounts());
            $totalDiscountUnitGrossAmount += $this->getCalculatedDiscountsUnitGrossAmount($optionTransfer->getCalculatedDiscounts());
            $totalDiscountSumGrossAmount += $this->getCalculatedDiscountsSumGrossAmount($optionTransfer->getCalculatedDiscounts());
        }

        return [$totalDiscountUnitGrossAmount, $totalDiscountSumGrossAmount];
    }

    /**
     * @param \ArrayObject|ProductOptionTransfer[] $productOptionTransfer
     *
     * @return int
     */
    protected function getProductOptionGrossUnitTotalAmount(\ArrayObject $productOptionTransfer)
    {
        $totalDiscountUnitGrossAmount = 0;
        foreach ($productOptionTransfer as $optionTransfer) {
            $this->setCalculatedDiscountsSumGrossAmount($optionTransfer->getCalculatedDiscounts());
            $totalDiscountUnitGrossAmount += $this->getCalculatedDiscountsUnitGrossAmount($optionTransfer->getCalculatedDiscounts());
        }

        return $totalDiscountUnitGrossAmount;
    }

    /**
     * @param \ArrayObject|ProductOptionTransfer[] $productOptionTransfer
     *
     * @return int
     */
    protected function getProductOptionGrossSumTotalAmount(\ArrayObject $productOptionTransfer)
    {
        $totalDiscountSumGrossAmount = 0;
        foreach ($productOptionTransfer as $optionTransfer) {
            $this->setCalculatedDiscountsSumGrossAmount($optionTransfer->getCalculatedDiscounts());
            $totalDiscountSumGrossAmount += $this->getCalculatedDiscountsSumGrossAmount($optionTransfer->getCalculatedDiscounts());
        }

        return $totalDiscountSumGrossAmount;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function setExpenseGrossAmounts(QuoteTransfer $quoteTransfer)
    {
        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            $this->setCalculatedDiscountsSumGrossAmount($expenseTransfer->getCalculatedDiscounts());
            $unitAmount = $this->getCalculatedDiscountsUnitGrossAmount($expenseTransfer->getCalculatedDiscounts());
            $sumAmount = $this->getCalculatedDiscountsSumGrossAmount($expenseTransfer->getCalculatedDiscounts());

            $expenseTransfer->setUnitGrossPriceWithDiscounts(
                $expenseTransfer->getUnitGrossPrice() - $unitAmount
            );

            $expenseTransfer->setSumGrossPriceWithDiscounts(
                $expenseTransfer->getSumGrossPrice() - $sumAmount
            );
        }
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function calculateItemGrossAmounts(QuoteTransfer $quoteTransfer)
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->setItemGrossAmounts($itemTransfer);
        }
    }

    /**
     * @param CalculatedDiscountTransfer $calculatedDiscountTransfer
     *
     * @return void
     */
    protected function assertCalculatedDiscountRequirements(CalculatedDiscountTransfer $calculatedDiscountTransfer)
    {
        $calculatedDiscountTransfer->requireQuantity()->requireUnitGrossAmount();
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     */
    protected function assertDiscountTotalRequirements(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->requireTotals();
    }


}
