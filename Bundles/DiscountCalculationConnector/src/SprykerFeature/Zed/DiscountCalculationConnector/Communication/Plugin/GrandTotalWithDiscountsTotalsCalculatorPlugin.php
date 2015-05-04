<?php

namespace SprykerFeature\Zed\DiscountCalculationConnector\Communication\Plugin;

use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableContainerInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableItemCollectionInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\TotalsInterface;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableContainerInterface;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableItemCollectionInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;
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
