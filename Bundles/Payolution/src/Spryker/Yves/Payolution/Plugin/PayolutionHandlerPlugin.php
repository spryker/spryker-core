<?php
/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */
namespace Spryker\Yves\Payolution\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Checkout\Dependency\Plugin\CheckoutStepHandlerPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Yves\Payolution\PayolutionFactory getFactory()
 */
class PayolutionHandlerPlugin extends AbstractPlugin implements CheckoutStepHandlerPluginInterface
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addToQuote(Request $request, QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->createPayolutionHandler()->addPaymentToQuote($request, $quoteTransfer);
    }

}
