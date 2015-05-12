<?php

namespace SprykerFeature\Zed\DiscountCalculationConnector\Dependency\Facade;

use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableContainerInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\TotalsInterface;

interface DiscountCalculationToCalculationInterface
{

    /**
     * @param TotalsInterface $totalsTransfer
     * @param CalculableContainerInterface $calculableContainer
     * @param \ArrayObject $calculableItems
     */
    public function recalculateGrandTotalTotals(
        TotalsInterface $totalsTransfer,
        CalculableContainerInterface $calculableContainer,
        \ArrayObject $calculableItems
    );
}
