<?php

namespace Spryker\Yves\Braintree\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Checkout\Dependency\Plugin\CheckoutStepHandlerPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Yves\Braintree\BraintreeFactory getFactory()
 */
class BraintreeHandlerPlugin extends AbstractPlugin implements CheckoutStepHandlerPluginInterface
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addToQuote(Request $request, QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->createBraintreeHandler()->addPaymentToQuote($request, $quoteTransfer);
    }

}
