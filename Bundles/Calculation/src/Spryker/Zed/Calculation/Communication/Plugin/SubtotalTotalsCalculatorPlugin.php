<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Calculation\Communication\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Calculation\Business\CalculationFacade;
use Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Calculation\Communication\CalculationCommunicationFactory;

/**
 * @method CalculationFacade getFacade()
 * @method CalculationCommunicationFactory getFactory()
 */
class SubtotalTotalsCalculatorPlugin extends AbstractPlugin implements CalculatorPluginInterface
{
    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $this->getFacade()->calculateSubtotalTotals($quoteTransfer);
    }
}
