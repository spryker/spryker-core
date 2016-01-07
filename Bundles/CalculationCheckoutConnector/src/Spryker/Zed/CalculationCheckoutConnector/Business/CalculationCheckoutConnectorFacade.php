<?php

namespace Spryker\Zed\CalculationCheckoutConnector\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CalculationCheckoutConnector\Business\CalculationCheckoutConnectorBusinessFactory getFactory()
 */
class CalculationCheckoutConnectorFacade extends AbstractFacade implements CalculationCheckoutConnectorFacadeInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $response
     *
     * @return void
     */
    public function validateCartGrandTotal(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $response)
    {
        $this->getFactory()->createCartAmountPrecondition()->validateCartGrandTotal($quoteTransfer, $response);
    }
}
