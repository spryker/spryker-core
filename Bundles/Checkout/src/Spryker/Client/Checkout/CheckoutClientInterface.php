<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface CheckoutClientInterface
{

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return CheckoutResponseTransfer
     */
    public function requestCheckout(QuoteTransfer $quoteTransfer);

}
