<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Calculation\OrderInterface;
use Generated\Shared\Transfer\TotalsTransfer;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;

class RemoveTotalsCalculator implements CalculatorPluginInterface
{

    /**
     * @param OrderInterface $calculableContainer
     */
    public function recalculate(OrderInterface $calculableContainer)
    {
        $calculableContainer->setTotals(new TotalsTransfer());
    }
}
