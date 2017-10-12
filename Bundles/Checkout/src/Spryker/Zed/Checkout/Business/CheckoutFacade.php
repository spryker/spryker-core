<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
     * - Run checkout pre-condition plugins (return on error)
     * - Run checkout order saver plugins (in a transaction)
     * - Trigger state machine for all items of the new order (-> Oms)
     * - Run post-hook plugins
     * - Returns response with boolean isSuccess
     *
     * @api
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
