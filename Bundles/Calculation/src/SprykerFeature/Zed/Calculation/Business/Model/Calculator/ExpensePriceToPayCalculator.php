<?php

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableContainerInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\ExpenseItemInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;

class ExpensePriceToPayCalculator extends AbstractCalculator implements
    CalculatorPluginInterface
{
    /**
     * @param CalculableContainerInterface $calculableContainer
     */
    public function recalculate(CalculableContainerInterface $calculableContainer)
    {
        foreach ($calculableContainer->getItems() as $item) {
            foreach ($item->getExpenses() as $expense) {
                $expense->setPriceToPay($expense->getGrossPrice() - $this->getExpenseDiscountAmount($expense));
            }
        }

        foreach ($calculableContainer->getExpenses() as $expense) {
            $expense->setPriceToPay($expense->getGrossPrice() - $this->getExpenseDiscountAmount($expense));
        }
    }

    /**
     * @param ExpenseItemInterface $expense
     * @return int
     */
    protected function getExpenseDiscountAmount(ExpenseItemInterface $expense)
    {
        $discountAmount = 0;
        foreach ($expense->getDiscounts() as $discount) {
            $discountAmount += $discount->getAmount();
        }

        return $discountAmount;
    }
}
