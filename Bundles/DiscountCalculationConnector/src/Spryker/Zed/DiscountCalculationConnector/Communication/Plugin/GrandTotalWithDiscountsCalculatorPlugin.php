<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCalculationConnector\Communication\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\DiscountCalculationConnector\Communication\DiscountCalculationConnectorCommunicationFactory;

/**
 * @method DiscountCalculationConnectorCommunicationFactory getFactory()
 */
class GrandTotalWithDiscountsCalculatorPlugin extends AbstractPlugin implements CalculatorPluginInterface
{

    /**
     * @param QuoteTransfer $quoteTransfer
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->getDiscountCalculationFacade()->calculateGrandTotalWithDiscounts($quoteTransfer);
    }
}
