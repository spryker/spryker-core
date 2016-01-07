<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Checkout\Zed;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface CheckoutStubInterface
{

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return CheckoutResponseTransfer
     */
    public function requestCheckout(QuoteTransfer $quoteTransfer);

}
