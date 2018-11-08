<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CheckoutRestApi;

use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface CheckoutRestApiClientInterface
{
    /**
     * Specification:
     * - Takes Quote transfer and returns Checkout data.
     * - Checkout data will include available shipping methods, available payment methods and available customer addresses.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutDataTransfer
     */
    public function getCheckoutData(QuoteTransfer $quoteTransfer): CheckoutDataTransfer;

    /**
     * Specification:
     * - Takes Quote transfer.
     * - Validates the Quote.
     * - Places an order with the passed Quote.
     * - Response is CheckoutResponseTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function placeOrder(QuoteTransfer $quoteTransfer): CheckoutResponseTransfer;
}
