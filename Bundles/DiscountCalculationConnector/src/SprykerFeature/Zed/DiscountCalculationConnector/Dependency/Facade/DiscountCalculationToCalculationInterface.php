<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\DiscountCalculationConnector\Dependency\Facade;

use Generated\Shared\Calculation\OrderInterface;
use Generated\Shared\Calculation\TotalsInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;

interface DiscountCalculationToCalculationInterface
{

    /**
     * @param TotalsInterface $totalsTransfer
     * @ param OrderInterface $calculableContainer
     * @param CalculableInterface $calculableContainer
     * @param \ArrayObject $calculableItems
     */
    public function recalculateGrandTotalTotals(
        TotalsInterface $totalsTransfer,
        //OrderInterface $calculableContainer,
        CalculableInterface $calculableContainer,
        \ArrayObject $calculableItems
    );
}
