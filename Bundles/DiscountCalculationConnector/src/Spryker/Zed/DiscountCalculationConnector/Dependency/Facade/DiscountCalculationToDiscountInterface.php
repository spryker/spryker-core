<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCalculationConnector\Dependency\Facade;

use Generated\Shared\Transfer\QuoteTransfer;

interface DiscountCalculationToDiscountInterface
{

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return mixed
     */
    public function calculateDiscounts(QuoteTransfer $quoteTransfer);

}
