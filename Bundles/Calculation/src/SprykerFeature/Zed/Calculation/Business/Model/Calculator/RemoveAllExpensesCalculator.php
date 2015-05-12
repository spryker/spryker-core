<?php

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\CalculationExpenseTransfer;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableContainerInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;

class RemoveAllExpensesCalculator implements
    CalculatorPluginInterface
{
    /**
     * @param CalculableContainerInterface $calculableContainer
     */
    public function recalculate(CalculableContainerInterface $calculableContainer)
    {
        foreach ($calculableContainer->getItems() as $item) {
            $item->setExpenses(new \ArrayObject());
        }

        $calculableContainer->setExpenses(new CalculationExpenseTransfer());
    }
}
