<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Calculation\Dependency\Plugin;

use Generated\Shared\Calculation\TotalsInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;

interface TotalsCalculatorPluginInterface
{

    /**
     * @param TotalsInterface $totalsTransfer
     * @param CalculableInterface $calculableContainer
     * @param $calculableItems
     */
    public function recalculateTotals(
        TotalsInterface $totalsTransfer,
        CalculableInterface $calculableContainer,
        $calculableItems
    );

}
