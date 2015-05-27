<?php

namespace SprykerFeature\Zed\Calculation\Communication\Plugin;

use Generated\Shared\Calculation\OrderInterface;
use Generated\Shared\Calculation\TotalsInterface;
use SprykerFeature\Zed\Calculation\Communication\CalculationDependencyContainer;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method CalculationDependencyContainer getDependencyContainer()
 */
class ExpenseTotalsCalculatorPlugin extends AbstractPlugin implements TotalsCalculatorPluginInterface
{

    /**
     * @param TotalsInterface $totalsTransfer
     * @param OrderInterface $calculableContainer
     * @param \ArrayObject $calculableItems
     */
    public function recalculateTotals(
        TotalsInterface $totalsTransfer,
        OrderInterface $calculableContainer,
        \ArrayObject $calculableItems
    ) {
        $this->getDependencyContainer()
            ->getCalculationFacade()
            ->recalculateExpenseTotals($totalsTransfer, $calculableContainer, $calculableItems)
        ;
    }
}
