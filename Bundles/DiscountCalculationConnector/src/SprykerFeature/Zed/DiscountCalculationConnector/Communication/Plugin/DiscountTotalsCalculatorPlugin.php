<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\DiscountCalculationConnector\Communication\Plugin;

use Generated\Shared\Calculation\TotalsInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;
use SprykerFeature\Zed\DiscountCalculationConnector\Communication\DiscountCalculationConnectorDependencyContainer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method DiscountCalculationConnectorDependencyContainer getDependencyContainer()
 */
class DiscountTotalsCalculatorPlugin extends AbstractPlugin implements TotalsCalculatorPluginInterface
{

    /**
     * @param TotalsInterface $totalsTransfer
     * @param CalculableInterface $calculableContainer
     * @param $calculableItems
     */
    public function recalculateTotals(
        TotalsInterface $totalsTransfer,
        CalculableInterface $calculableContainer,
        $calculableItems
    ) {
        $this->getDependencyContainer()
            ->getDiscountCalculationFacade()
            ->recalculateDiscountTotals($totalsTransfer, $calculableContainer, $calculableItems)
        ;
    }

}
