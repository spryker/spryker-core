<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Checkout;

use Generated\Shared\Transfer\CanProceedCheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface CheckoutClientInterface
{
    /**
     * Specification:
     * - Places the order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function placeOrder(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Returns CanProceedCheckoutResponseTransfer.
     * - If CanProceedCheckoutResponseTransfer property `isSuccessful` than quote is applicable for checkout otherwise it is't.
     * - May return array of messages in `messages` propery of CanProceedCheckoutResponseTransfer transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CanProceedCheckoutResponseTransfer
     */
    public function isQuoteApplicableForCheckout(QuoteTransfer $quoteTransfer): CanProceedCheckoutResponseTransfer;
}
