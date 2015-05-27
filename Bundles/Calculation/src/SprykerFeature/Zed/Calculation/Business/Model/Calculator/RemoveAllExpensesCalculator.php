<?php

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Calculation\OrderInterface;
use Generated\Shared\Transfer\ExpenseTransfer;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;

class RemoveAllExpensesCalculator implements
    CalculatorPluginInterface
{
    /**
     * @param OrderInterface $calculableContainer
     */
    public function recalculate(OrderInterface $calculableContainer)
    {
        foreach ($calculableContainer->getItems() as $item) {
            $item->setExpenses(new \ArrayObject());
        }

        $calculableContainer->setExpenses(new ExpenseTransfer());
    }
}
