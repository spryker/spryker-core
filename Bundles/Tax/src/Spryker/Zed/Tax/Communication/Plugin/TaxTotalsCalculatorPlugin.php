<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Tax\Communication\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Tax\Business\TaxFacade;
use Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;

/**
 * @method \Spryker\Zed\Tax\Business\TaxFacade getFacade()
 */
class TaxTotalsCalculatorPlugin extends AbstractPlugin implements CalculatorPluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $this->getFacade()->calculateTaxTotals($quoteTransfer);
    }
}
