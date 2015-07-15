<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Calculation\ExpenseInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;

class ExpensePriceToPayCalculator implements CalculatorPluginInterface
{

    /**
     * @param CalculableInterface $calculableContainer
     */
    public function recalculate(CalculableInterface $calculableContainer)
    {
        foreach ($calculableContainer->getCalculableObject()->getItems() as $item) {
            foreach ($item->getExpenses() as $expense) {
                $expense->setPriceToPay($expense->getGrossPrice() - $this->getExpenseDiscountAmount($expense));
            }
        }

        foreach ($calculableContainer->getCalculableObject()->getExpenses() as $expense) {
            $expense->setPriceToPay($expense->getGrossPrice() - $this->getExpenseDiscountAmount($expense));
        }
    }

    /**
     * @param ExpenseInterface $expense
     *
     * @return int
     */
    protected function getExpenseDiscountAmount(ExpenseInterface $expense)
    {
        $discountAmount = 0;
        foreach ($expense->getDiscounts() as $discount) {
            $discountAmount += $discount->getAmount();
        }

        return $discountAmount;
    }

}
