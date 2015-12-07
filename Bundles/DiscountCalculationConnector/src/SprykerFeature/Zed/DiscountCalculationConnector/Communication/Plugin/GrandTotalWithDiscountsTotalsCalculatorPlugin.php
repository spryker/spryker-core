<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\DiscountCalculationConnector\Communication\Plugin;

use Generated\Shared\Transfer\TotalsTransfer;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;
use SprykerFeature\Zed\DiscountCalculationConnector\Communication\DiscountCalculationConnectorDependencyContainer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method DiscountCalculationConnectorDependencyContainer getDependencyContainer()
 */
class GrandTotalWithDiscountsTotalsCalculatorPlugin extends AbstractPlugin implements TotalsCalculatorPluginInterface
{

    /**
     * @param TotalsTransfer $totalsTransfer
     * @param CalculableInterface $calculableContainer
     * @param $calculableItems
     *
     * @return void
     */
    public function recalculateTotals(
        TotalsTransfer $totalsTransfer,
        CalculableInterface $calculableContainer,
        $calculableItems
    ) {
        $this
            ->getDependencyContainer()
            ->getDiscountCalculationFacade()
            ->recalculateGrandTotalWithDiscountsTotals($totalsTransfer, $calculableContainer, $calculableItems);
    }

}
