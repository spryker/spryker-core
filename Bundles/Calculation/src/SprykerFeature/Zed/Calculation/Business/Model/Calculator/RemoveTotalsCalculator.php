<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Calculation\OrderInterface;
use Generated\Shared\Transfer\TotalsTransfer;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;

class RemoveTotalsCalculator implements CalculatorPluginInterface
{

    /**
     * @ param OrderInterface $calculableContainer
     * @param CalculableInterface $calculableContainer
     */
    public function recalculate(CalculableInterface $calculableContainer)
    //public function recalculate(OrderInterface $calculableContainer)
    {
        //$$calculableContainer->getCalculableObject()->setTotals(new \ArrayObject());
        $calculableContainer->getCalculableObject()->setTotals(new TotalsTransfer());
    }
}
