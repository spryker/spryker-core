<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\ExpenseTransfer;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;
use Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;

class ExpensePriceToPayCalculator implements CalculatorPluginInterface
{

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     *
     * @return void
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
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expense
     *
     * @return int
     */
    protected function getExpenseDiscountAmount(ExpenseTransfer $expense)
    {
        $discountAmount = 0;
        foreach ($expense->getDiscounts() as $discount) {
            $discountAmount += $discount->getAmount();
        }

        return $discountAmount;
    }

}
