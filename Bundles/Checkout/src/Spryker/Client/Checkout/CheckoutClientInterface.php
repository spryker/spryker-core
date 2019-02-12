<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Checkout;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteValidationResponseTransfer;

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
     * - Executes QuoteProceedCheckoutCheckPluginInterface plugins, if at least one plugin returns not successful response that `isSuccess` property will be false.
     * - Successful if no `QuoteProceedCheckoutCheckPluginInterface` plugins registered.
     * - Quote is applicable for checkout if response successful.
     * - May return array of messages in response is not successful.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    public function isQuoteApplicableForCheckout(QuoteTransfer $quoteTransfer): QuoteValidationResponseTransfer;
}
