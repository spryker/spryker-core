<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Checkout\Business;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Checkout\Business\CheckoutBusinessFactory getFactory()
 */
class CheckoutFacade extends AbstractFacade implements CheckoutFacadeInterface
{

    /**
     * Specification:
     * - Run checkout precondition plugins (return on error)
     * - Run checkout order saver plugins (in a transaction)
     * - Trigger state machine for all items of the new order (-> Oms)
     * - Run post-hook plugins
     * - Returns response with boolean isSuccess
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function placeOrder(QuoteTransfer $quoteTransfer)
    {
        return $this
            ->getFactory()
            ->createCheckoutWorkflow()
            ->placeOrder($quoteTransfer);
    }

}
