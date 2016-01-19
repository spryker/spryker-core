<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Cart\Dependency\Facade;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;

interface CartToCalculationInterface
{
    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return QuoteTransfer
     */
    public function recalculate(QuoteTransfer $quoteTransfer);

}
