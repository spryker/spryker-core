<?php

namespace SprykerFeature\Zed\DiscountCalculationConnector\Communication\Plugin;

use Generated\Shared\Transfer\Calculation\DependencyTotalsInterfaceTransfer;
use Generated\Shared\Transfer\Discount\DependencyDiscountableContainerInterfaceTransfer;
use Generated\Shared\Transfer\Discount\DependencyDiscountableItemCollectionInterfaceTransfer;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;
use Generated\Shared\Transfer\Calculation\DependencyCalculableContainerInterfaceTransfer;
use Generated\Shared\Transfer\Calculation\DependencyCalculableItemCollectionInterfaceTransfer;
use SprykerFeature\Zed\DiscountCalculationConnector\Communication\DiscountCalculationConnectorDependencyContainer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method DiscountCalculationConnectorDependencyContainer getDependencyContainer()
 */
class GrandTotalWithDiscountsTotalsCalculatorPlugin extends AbstractPlugin implements
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
        if ($calculableContainer instanceof DiscountableContainerInterface &&
            $calculableItems instanceof DiscountableItemCollectionInterface) {
            $this->getDependencyContainer()
                ->getDiscountCalculationFacade()
                ->recalculateGrandTotalWithDiscountsTotals($totalsTransfer, $calculableContainer, $calculableItems);
        }
    }
}
