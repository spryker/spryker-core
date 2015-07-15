<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\DiscountCalculationConnector\Dependency\Facade;

use Generated\Shared\Calculation\TotalsInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;

interface DiscountCalculationToCalculationInterface
{

    /**
     * @param TotalsInterface $totalsTransfer
     * @param CalculableInterface $calculableContainer
     * @param $calculableItems
     */
    public function recalculateGrandTotalTotals(
        TotalsInterface $totalsTransfer,
        CalculableInterface $calculableContainer,
        $calculableItems
    );

}
