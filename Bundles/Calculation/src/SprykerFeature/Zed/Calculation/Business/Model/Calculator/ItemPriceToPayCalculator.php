<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Calculation\ItemInterface;
use Generated\Shared\Calculation\DiscountInterface;
use Generated\Shared\Calculation\ExpenseInterface;
use Generated\Shared\Calculation\ProductOptionInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;

class ItemPriceToPayCalculator implements CalculatorPluginInterface
{

    /**
     * @param CalculableInterface $calculableContainer
     */
    public function recalculate(CalculableInterface $calculableContainer)
    {
        $items = $this->getItems($calculableContainer);
        foreach ($items as $item) {

            $priceToPay = $item->getGrossPrice();
            $priceToPay -= $this->sumDiscounts($item->getDiscounts());

            $priceToPay = max(0, $priceToPay);
            if ($priceToPay === 0) {
                $priceToPay = $item->getGrossPrice();
            }

            $priceToPay += $this->sumOptions($item->getProductOptions());

            $item->setPriceToPay($priceToPay);
        }
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
     * @param CalculableInterface $calculableContainer
     *
     * @return ItemInterface[]
     */
    protected function getItems(CalculableInterface $calculableContainer)
    {
        return $calculableContainer->getCalculableObject()->getItems();
    }

}
