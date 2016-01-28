<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;

class DiscountTotalsCalculator implements CalculatorInterface
{

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->requireTotals();

        $totalDiscountAmount = $this->sumCalculatedDiscounts($quoteTransfer);
        $quoteTransfer->getTotals()->setDiscountTotal($totalDiscountAmount);
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    public function sumCalculatedDiscounts(QuoteTransfer $quoteTransfer)
    {
        $discountAmount = 0;

        $discountAmount += $this->calculateItemDiscounts($quoteTransfer);
        $discountAmount += $this->calculateExpenseTotalDiscountAmount($quoteTransfer);

        return $discountAmount;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function calculateItemDiscounts(QuoteTransfer $quoteTransfer)
    {
        $discountAmount = 0;
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $discountAmount += $this->getItemTotalDiscountAmount($itemTransfer);
        }

        return $discountAmount;
    }

    /**
     * @param ItemTransfer $itemTransfer
     *
     * @return int
     */
    protected function getItemTotalDiscountAmount(ItemTransfer $itemTransfer)
    {
        $totalDiscountSumGrossAmount = 0;
        $totalDiscountUnitGrossAmount = 0;

        list ($itemUnitAmount, $itemSumAmount) = $this->getSumOfCalculatedDiscounts(
            $itemTransfer->getCalculatedDiscounts()
        );

        $totalDiscountUnitGrossAmount += $itemUnitAmount;
        $totalDiscountSumGrossAmount += $itemSumAmount;

        list ($itemOptionUnitAmount, $itemOptionSumAmount) = $this->getSumOfProductOptionCalculatedDiscounts(
            $itemTransfer->getProductOptions()
        );

        $totalDiscountUnitGrossAmount += $itemOptionUnitAmount;
        $totalDiscountSumGrossAmount += $itemOptionSumAmount;

        $this->addDiscountToItemGrossTotals($itemTransfer, $totalDiscountUnitGrossAmount, $totalDiscountSumGrossAmount);

        return $totalDiscountSumGrossAmount;
    }

    /**
     * @param \ArrayObject|CalculatedDiscountTransfer[] $calculatedDiscounts
     *
     * @return int
     */
    protected function getSumOfCalculatedDiscounts(\ArrayObject $calculatedDiscounts)
    {
        $totalDiscountSumGrossAmount = 0;
        $totalDiscountUnitGrossAmount = 0;
        foreach ($calculatedDiscounts as $calculatedDiscountTransfer) {
            $calculatedDiscountTransfer->requireQuantity()->requireUnitGrossAmount();

            $calculatedDiscountTransfer->setSumGrossAmount(
                $calculatedDiscountTransfer->getUnitGrossAmount() * $calculatedDiscountTransfer->getQuantity()
            );

            $totalDiscountSumGrossAmount += $calculatedDiscountTransfer->getSumGrossAmount();
            $totalDiscountUnitGrossAmount += $calculatedDiscountTransfer->getUnitGrossAmount();
        }

        return [$totalDiscountUnitGrossAmount, $totalDiscountSumGrossAmount];
    }

    /**
     * @param \ArrayObject|ProductOptionTransfer[] $options
     *
     * @return int
     */
    protected function getSumOfProductOptionCalculatedDiscounts(\ArrayObject $options)
    {
        $totalDiscountUnitGrossAmount = 0;
        $totalDiscountSumGrossAmount = 0;
        foreach ($options as $optionTransfer) {
            list ($unitAmount, $sumAmount) = $this->getSumOfCalculatedDiscounts(
                $optionTransfer->getCalculatedDiscounts()
            );
            $totalDiscountUnitGrossAmount += $unitAmount;
            $totalDiscountSumGrossAmount += $sumAmount;
        }

        return [$totalDiscountUnitGrossAmount, $totalDiscountSumGrossAmount];
    }

    /**
     * @param ItemTransfer $itemTransfer
     * @param int $totalDiscountUnitGrossAmount
     * @param int $totalDiscountSumGrossAmount
     *
     * @return void
     */
    protected function addDiscountToItemGrossTotals(
        ItemTransfer $itemTransfer,
        $totalDiscountUnitGrossAmount,
        $totalDiscountSumGrossAmount
    ){
        $itemTransfer->setSumGrossPriceWithProductOptionAndDiscountAmounts(
            $itemTransfer->getSumGrossPriceWithProductOptions() - $totalDiscountSumGrossAmount
        );

        $itemTransfer->setUnitGrossPriceWithProductOptionAndDiscountAmounts(
            $itemTransfer->getUnitGrossPriceWithProductOptions() - $totalDiscountUnitGrossAmount
        );
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function calculateExpenseTotalDiscountAmount(QuoteTransfer $quoteTransfer)
    {
        $totalDiscountSumGrossAmount = 0;
        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            list ($unitAmount, $sumAmount) = $this->getSumOfCalculatedDiscounts($expenseTransfer->getCalculatedDiscounts());

            $expenseTransfer->setUnitGrossPriceWithDiscounts(
                $expenseTransfer->getUnitGrossPrice() - $unitAmount
            );

            $expenseTransfer->setSumGrossPriceWithDiscounts(
                $expenseTransfer->getSumGrossPrice() - $sumAmount
            );

            $totalDiscountSumGrossAmount += $sumAmount;
        }

        return $totalDiscountSumGrossAmount;
    }

}
