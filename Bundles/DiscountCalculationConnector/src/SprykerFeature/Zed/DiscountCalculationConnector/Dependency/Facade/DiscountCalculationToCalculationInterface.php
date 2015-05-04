<?php

namespace SprykerFeature\Zed\DiscountCalculationConnector\Dependency\Facade;

use Generated\Shared\Transfer\Calculation\DependencyTotalsInterfaceTransfer;
use Generated\Shared\Transfer\Calculation\DependencyCalculableContainerInterfaceTransfer;
use Generated\Shared\Transfer\Calculation\DependencyCalculableItemCollectionInterfaceTransfer;

interface DiscountCalculationToCalculationInterface
{
    /**
     * @param TotalsInterface $totalsTransfer
     * @param CalculableContainerInterface $calculableContainer
     * @param CalculableItemCollectionInterface $calculableItems
     */
    public function recalculateGrandTotalTotals(
        TotalsInterface $totalsTransfer,
        CalculableContainerInterface $calculableContainer,
        CalculableItemCollectionInterface $calculableItems
    );
}
