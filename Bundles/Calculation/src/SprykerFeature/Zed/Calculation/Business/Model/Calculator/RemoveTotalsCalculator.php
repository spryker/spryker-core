<?php

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableContainerInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;

class RemoveTotalsCalculator extends AbstractCalculator implements
    CalculatorPluginInterface
{
    /**
     * @param CalculableContainerInterface $calculableContainer
     */
    public function recalculate(CalculableContainerInterface $calculableContainer)
    {
        $calculableContainer->setTotals(new \Generated\Shared\Transfer\CalculationTotalsTransfer());
    }
}
