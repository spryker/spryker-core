<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Checkout\Dependency\Plugin;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * @deprecated Use \Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutDoSaveOrderInterface instead
 */
interface CheckoutSaveOrderInterface
{
    /**
     * Specification:
     * - This plugin retrieves (its) data from the quote object and saves it to the database.
     * - These plugins are already enveloped into a transaction.
     * - Fills SaveOrderTransfer
     * - Does not change Checkout Response
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse);
}
