<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutRestApi\Business;

use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface CheckoutRestApiFacadeInterface
{
    /**
     * Specification:
     * - Takes QuoteTransfer and returns CheckoutDataTransfer.
     * - Response will contain the payment and shipment methods
     * filtered by the provided Quote data (addresses, payment and shipment methods).
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
     * - Takes QuoteTransfer and places order with it.
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
