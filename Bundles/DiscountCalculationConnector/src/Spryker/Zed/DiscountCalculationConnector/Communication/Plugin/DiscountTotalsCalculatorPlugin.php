<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCalculationConnector\Communication\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;
use Spryker\Zed\DiscountCalculationConnector\Business\DiscountCalculationConnectorFacade;

/**
 * @method DiscountCalculationConnectorFacade getFacade()
 */
class DiscountTotalsCalculatorPlugin extends AbstractPlugin implements CalculatorPluginInterface
{

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $this->getFacade()->calculateDiscountTotals($quoteTransfer);
    }

}
