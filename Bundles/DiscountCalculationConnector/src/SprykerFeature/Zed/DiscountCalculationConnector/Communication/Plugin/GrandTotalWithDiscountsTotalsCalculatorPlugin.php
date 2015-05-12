<?php

namespace SprykerFeature\Zed\DiscountCalculationConnector\Communication\Plugin;

use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableContainerInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\TotalsInterface;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableContainerInterface;
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
     * @param \ArrayObject $calculableItems
     */
    public function recalculateTotals(
        TotalsInterface $totalsTransfer,
        CalculableContainerInterface $calculableContainer,
        \ArrayObject $calculableItems
    ) {
        if ($calculableContainer instanceof DiscountableContainerInterface) {
            $this->getDependencyContainer()
                ->getDiscountCalculationFacade()
                ->recalculateGrandTotalWithDiscountsTotals($totalsTransfer, $calculableContainer, $calculableItems);
        }
    }
}
