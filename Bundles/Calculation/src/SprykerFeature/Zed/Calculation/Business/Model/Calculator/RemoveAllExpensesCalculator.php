<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator;

use Spryker\Zed\Calculation\Business\Model\CalculableInterface;
use Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;

class RemoveAllExpensesCalculator implements
    CalculatorPluginInterface
{

    /**
     * @param CalculableInterface $calculableContainer
     *
     * @return void
     */
    public function recalculate(CalculableInterface $calculableContainer)
    {
        foreach ($calculableContainer->getCalculableObject()->getItems() as $item) {
            $item->setExpenses(new \ArrayObject());
        }
    }

}
