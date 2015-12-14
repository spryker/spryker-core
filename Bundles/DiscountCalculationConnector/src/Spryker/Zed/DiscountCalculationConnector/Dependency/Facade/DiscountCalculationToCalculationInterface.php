<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCalculationConnector\Dependency\Facade;

use Generated\Shared\Transfer\QuoteTransfer;

interface DiscountCalculationToCalculationInterface
{

    /**
     * @param QuoteTransfer $quoteTransfer
     */
    public function calculateGrandTotalTotals(QuoteTransfer $quoteTransfer);

}
