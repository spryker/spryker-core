<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Calculation\Communication\Plugin;

use Generated\Shared\Calculation\TotalsInterface;
use SprykerFeature\Zed\Calculation\Business\CalculationFacade;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method CalculationFacade getFacade()
 */
class SubtotalTotalsCalculatorPlugin extends AbstractPlugin implements TotalsCalculatorPluginInterface
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
        $this->getFacade()
            ->recalculateSubtotalTotals($totalsTransfer, $calculableContainer, $calculableItems)
        ;
    }

}
