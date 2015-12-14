<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCalculationConnector\Communication\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\DiscountCalculationConnector\Communication\DiscountCalculationConnectorCommunicationFactory getFactory()
 * @method \Spryker\Zed\DiscountCalculationConnector\Business\DiscountCalculationConnectorFacade getFacade()
 */
class DiscountCalculatorPlugin extends AbstractPlugin implements CalculatorPluginInterface
{

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()->getDiscountFacade()->calculateDiscounts($quoteTransfer);
    }

}
