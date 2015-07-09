<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Calculation\Communication\Plugin;

use SprykerFeature\Zed\Calculation\Business\CalculationFacade;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method CalculationFacade getFacade()
 */
class RemoveAllExpensesCalculatorPlugin extends AbstractPlugin implements CalculatorPluginInterface
{

    /**
     * @param CalculableInterface $calculableContainer
     */
    public function recalculate(CalculableInterface $calculableContainer)
    {
        $this->getFacade()->recalculateRemoveAllExpenses($calculableContainer);
    }

}
