<?php

namespace SprykerFeature\Zed\Calculation\Communication\Plugin;

use SprykerFeature\Zed\Calculation\Communication\CalculationDependencyContainer;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableContainerInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method CalculationDependencyContainer getDependencyContainer()
 */
class RemoveAllExpensesCalculatorPlugin extends AbstractPlugin implements CalculatorPluginInterface
{

    /**
     * @param CalculableContainerInterface $calculableContainer
     */
    public function recalculate(CalculableContainerInterface $calculableContainer)
    {
        $this->getDependencyContainer()->getCalculationFacade()->recalculateRemoveAllExpenses($calculableContainer);
    }
}
