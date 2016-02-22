<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Checkout\Business\Workflow;

use Generated\Shared\Transfer\CheckoutRequestTransfer;

interface CheckoutWorkflowInterface
{

    /**
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $checkoutRequest
     *
     * @return \Generated\Shared\Transfer\CheckoutRequestTransfer
     */
    public function requestCheckout(CheckoutRequestTransfer $checkoutRequest);

}
