<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Checkout\Dependency\Plugin;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;

interface CheckoutStepExecutionInterface
{

    /**
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $checkoutRequest
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     */
    public function execute(CheckoutRequestTransfer $checkoutRequest, CheckoutResponseTransfer $checkoutResponse);

}
