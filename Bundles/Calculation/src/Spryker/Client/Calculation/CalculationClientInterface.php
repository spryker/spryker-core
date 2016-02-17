<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Spryker\Client\Calculation;

use Generated\Shared\Transfer\QuoteTransfer;

/**
 * @method \Spryker\Client\Calculation\CalculationFactory getFactory()
 */
interface CalculationClientInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function recalculate(QuoteTransfer $quoteTransfer);

}
