<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutRestApi\Business;

use Generated\Shared\Transfer\RestCheckoutDataResponseTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutResponseTransfer;

interface CheckoutRestApiFacadeInterface
{
    /**
     * Specification:
     * - Provides checkout data based on data passed in RestCheckoutRequestAttributesTransfer.
     * - Checkout data will include available shipping methods, available payment methods and available customer addresses.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseTransfer
     */
    public function getCheckoutData(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): RestCheckoutDataResponseTransfer;

    /**
     * Specification:
     * - Looks up the customer quote by uuid.
     * - Validates quote.
     * - Executes plugins that maps request data into QuoteTransfer.
     * - Recalculates quote.
     * - Places an order.
     * - Deletes quote if order was placed successfully.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutResponseTransfer
     */
    public function placeOrder(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): RestCheckoutResponseTransfer;
}
