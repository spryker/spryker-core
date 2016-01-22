<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Customer\Business\Model;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface CustomerOrderSaverInterface
{

    /**
     * @param QuoteTransfer $quoteTransfer
     * @param CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer);

}
