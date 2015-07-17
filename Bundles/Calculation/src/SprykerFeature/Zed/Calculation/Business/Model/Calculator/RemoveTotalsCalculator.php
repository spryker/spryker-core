<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\TotalsTransfer;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;

class RemoveTotalsCalculator implements CalculatorPluginInterface
{

    /**
     * @param CalculableInterface $calculableContainer
     */
    public function recalculate(CalculableInterface $calculableContainer)
    {
        $calculableContainer->getCalculableObject()->setTotals(new TotalsTransfer());
    }

}
