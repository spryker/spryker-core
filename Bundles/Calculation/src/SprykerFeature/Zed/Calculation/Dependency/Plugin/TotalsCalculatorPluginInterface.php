<?php

namespace SprykerFeature\Zed\Calculation\Dependency\Plugin;

use SprykerFeature\Shared\Calculation\Dependency\Transfer\TotalsInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableContainerInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableItemCollectionInterface;

interface TotalsCalculatorPluginInterface
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
}
