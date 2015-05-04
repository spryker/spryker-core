<?php

namespace SprykerFeature\Zed\Calculation\Communication\Plugin;

use Generated\Shared\Transfer\Calculation\DependencyTotalsInterfaceTransfer;
use SprykerFeature\Zed\Calculation\Communication\CalculationDependencyContainer;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;
use Generated\Shared\Transfer\Calculation\DependencyCalculableContainerInterfaceTransfer;
use Generated\Shared\Transfer\Calculation\DependencyCalculableItemCollectionInterfaceTransfer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method CalculationDependencyContainer getDependencyContainer()
 */
class SubtotalWithoutItemExpensesTotalsCalculatorPlugin extends AbstractPlugin implements
    TotalsCalculatorPluginInterface
{

    /**
     * @param TotalsInterface $totalsTransfer
     * @param CalculableContainerInterface $calculableContainer
     * @param CalculableItemCollectionInterface $calculableItems
     */
    public function recalculateTotals(
        TotalsInterface $totalsTransfer,
        CalculableContainerInterface $calculableContainer,
        CalculableItemCollectionInterface $calculableItems
    ) {
        $this->getDependencyContainer()
            ->getCalculationFacade()
            ->recalculateSubtotalWithoutItemExpensesTotals($totalsTransfer, $calculableContainer, $calculableItems)
        ;
    }
}
