<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Calculation\Dependency\Plugin;

use Generated\Shared\Transfer\TotalsTransfer;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;

interface TotalsCalculatorPluginInterface
{

    /**
     * @param TotalsTransfer $totalsTransfer
     * @param CalculableInterface $calculableContainer
     * @param \ArrayObject $calculableItems
     */
    public function recalculateTotals(
        TotalsTransfer $totalsTransfer,
        CalculableInterface $calculableContainer,
        $calculableItems
    );

}
