<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Calculation\ItemInterface;
use Generated\Shared\Calculation\DiscountInterface;
use Generated\Shared\Calculation\ExpenseInterface;
use Generated\Shared\Calculation\ProductOptionInterface;
use Generated\Shared\Transfer\ItemTransfer;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;

class ItemPriceToPayCalculator implements CalculatorPluginInterface
{

    /**
     * @param CalculableInterface $calculableContainer
     *
     * @return void
     */
    public function recalculate(CalculableInterface $calculableContainer)
    {
        $items = $this->getItems($calculableContainer);

        foreach ($items as $itemTransfer) {
            $this->calculatePriceToPay($itemTransfer);
            $this->calculatePriceToPayWithoutDiscounts($itemTransfer);
        }
    }

    /**
     * @param ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function calculatePriceToPay(ItemTransfer $itemTransfer)
    {
        $priceToPay = $itemTransfer->getGrossPrice();
        $priceToPay -= $this->sumDiscounts($itemTransfer->getDiscounts());

        $priceToPay = $this->adjustPriceWhenEmpty($itemTransfer, $priceToPay);

        //@todo add item expenses to priceToPay
        $priceToPay += $this->sumOptions($itemTransfer->getProductOptions());

        $itemTransfer->setPriceToPay($priceToPay);
    }

    /**
     * @param ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function calculatePriceToPayWithoutDiscounts(ItemTransfer $itemTransfer)
    {
        $priceToPayWithoutDiscount = $itemTransfer->getGrossPrice();
        $priceToPayWithoutDiscount += $this->sumOptionsGrossPrice($itemTransfer->getProductOptions());
        $itemTransfer->setPriceToPayWithoutDiscount($priceToPayWithoutDiscount);
    }

    /**
     * @param \ArrayObject|DiscountInterface[] $discounts
     *
     * @return int
     */
    protected function sumDiscounts(\ArrayObject $discounts)
    {
        $discountAmount = 0;
        foreach ($discounts as $discount) {
            $discountAmount += $discount->getAmount();
        }

        return $discountAmount;
    }

    /**
     * @param \ArrayObject|ExpenseInterface[] $expenses
     *
     * @return int
     */
    protected function sumExpenses(\ArrayObject $expenses)
    {
        $expenseAmount = 0;
        foreach ($expenses as $expense) {
            $expenseAmount += $expense->getPriceToPay();
        }

        return $expenseAmount;
    }

    /**
     * @param \ArrayObject|ProductOptionInterface[] $options
     *
     * @return int
     */
    protected function sumOptions(\ArrayObject $options)
    {
        $optionsAmount = 0;
        foreach ($options as $option) {
            $optionsAmount += $option->getPriceToPay();
        }

        return $optionsAmount;
    }

    /**
     * @param \ArrayObject|ProductOptionInterface[] $options
     *
     * @return int
     */
    protected function sumOptionsGrossPrice(\ArrayObject $options)
    {
        $optionsAmount = 0;
        foreach ($options as $option) {
            $optionsAmount += $option->getGrossPrice();
        }

        return $optionsAmount;
    }

    /**
     * @param CalculableInterface $calculableContainer
     *
     * @return ItemInterface[]
     */
    protected function getItems(CalculableInterface $calculableContainer)
    {
        return $calculableContainer->getCalculableObject()->getItems();
    }

    /**
     * //@todo why set to gross, why not keep 0?
     * @param ItemTransfer $itemTransfer
     * @param int $priceToPay
     *
     * @return int
     */
    protected function adjustPriceWhenEmpty(ItemTransfer $itemTransfer, $priceToPay)
    {
        $priceToPay = max(0, $priceToPay);
        if ($priceToPay === 0) {
            $priceToPay = $itemTransfer->getGrossPrice();
        }
        return $priceToPay;
    }

}
