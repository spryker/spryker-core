<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdsRestApi\Business;

use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface SalesOrderThresholdsRestApiFacadeInterface
{
    /**
     * Specification:
     * - Requires `CheckoutDataTransfer.quote` to be set.
     * - Requires `CheckoutDataTransfer.quote.currency` to be set.
     * - Finds applicable thresholds.
     * - Adds error messages if threshold conditions are not matched.
     * - Returns `CheckoutResponseTransfer.isSuccessful` equal to `true` if validation passed, `false` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function validateSalesOrderThresholdsCheckoutData(CheckoutDataTransfer $checkoutDataTransfer): CheckoutResponseTransfer;

    /**
     * Specification:
     * - Does nothing if `QuoteTransfer.totals` is not set.
     * - Requires `QuoteTransfer.currency` to be set.
     * - Finds applicable thresholds.
     * - Calculates diff between minimal order value threshold and order value amounts.
     * - Translates sales order threshold messages.
     * - Expands quote with sales order thresholds data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteWithSalesOrderThresholdValues(QuoteTransfer $quoteTransfer): QuoteTransfer;
}
