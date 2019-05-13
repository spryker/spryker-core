<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderPaymentsRestApi\Business;

use Generated\Shared\Transfer\UpdateOrderPaymentRequestTransfer;
use Generated\Shared\Transfer\UpdateOrderPaymentResponseTransfer;

interface OrderPaymentsRestApiFacadeInterface
{
    /**
     * Specification:
     * - Updates order payment information.
     * - Runs OrderPaymentUpdaterPluginInterface plugin stack.
     * - Returns UpdateOrderPaymentResponseTransfer::isSuccessful = true in case the request has been handled by appropriate plugin.
     * - Returns UpdateOrderPaymentResponseTransfer::isSuccessful = false in case the request has not been handled.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UpdateOrderPaymentRequestTransfer $updateOrderPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\UpdateOrderPaymentResponseTransfer
     */
    public function updateOrderPayment(
        UpdateOrderPaymentRequestTransfer $updateOrderPaymentRequestTransfer
    ): UpdateOrderPaymentResponseTransfer;
}
