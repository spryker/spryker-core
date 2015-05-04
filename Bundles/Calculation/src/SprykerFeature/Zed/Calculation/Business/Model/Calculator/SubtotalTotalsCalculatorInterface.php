<?php

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\Calculation\DependencyTotalsInterfaceTransfer;
use Generated\Shared\Transfer\Calculation\DependencyCalculableContainerInterfaceTransfer;
use Generated\Shared\Transfer\Calculation\DependencyCalculableItemCollectionInterfaceTransfer;

/**
 * Interface SubtotalTotalsCalculatorInterface
 * @package SprykerFeature\Zed\Calculation\Business\Model\Calculator
 */
interface SubtotalTotalsCalculatorInterface
{
    /**
     * @param TotalsInterface $totalsTransfer
     * @param CalculableContainerInterface $calculableContainer
     * @param CalculableItemCollectionInterface $calculableItems
     */
    public function recalculateTotals(
        TotalsInterface $totalsTransfer,
        CalculableContainerInterface $calculableContainer,
        CalculableItemCollectionInterface $calculableItems
    );

    /**
     * @param CalculableItemCollectionInterface $calculableItems
     * @return int
     */
    public function calculateSubtotal(CalculableItemCollectionInterface $calculableItems);
}
