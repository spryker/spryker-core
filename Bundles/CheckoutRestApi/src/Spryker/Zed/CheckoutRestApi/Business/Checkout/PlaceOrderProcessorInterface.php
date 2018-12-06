<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutRestApi\Business\Checkout;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutResponseTransfer;

interface PlaceOrderProcessorInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutResponseTransfer
     */
    public function placeOrder(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): RestCheckoutResponseTransfer;
}
